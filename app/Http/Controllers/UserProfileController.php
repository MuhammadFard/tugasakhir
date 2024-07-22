<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function getAllUsers()
    {
        Log::info('getAllUsers method called');
        $users = User::all();
        Log::info('Users retrieved: ' . $users->count());
        return response()->json($users);
    }
    public function show()
    {
        $user = auth()->user();
        $profile = UserProfile::firstOrCreate(['user_id' => $user->id]);

        $response = [
            'id' => $profile->id,
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'phone_number' => $profile->phone_number,
            'address' => $profile->address,
            'role' => $user->role,
            'profile_picture' => $profile->profile_picture ? asset('storage/' . $profile->profile_picture) : null,
            'created_at' => $profile->created_at,
            'updated_at' => $profile->updated_at,
        ];

        Log::info('User profile response:', $response);

        return response()->json($response);
    }


    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized. Only admin can view all users.'], 403);
        }

        try {
            $users = User::with('profile')->get()->map(function ($user) {
                return [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'phone_number' => $user->profile->phone_number ?? null,
                    'address' => $user->profile->address ?? null,
                    'profile_picture' => $user->profile && $user->profile->profile_picture 
                        ? asset('storage/' . $user->profile->profile_picture) 
                        : null,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ];
            });

            Log::info('All users fetched successfully', ['count' => $users->count()]);

            return response()->json($users);
        } catch (\Exception $e) {
            Log::error('Error fetching all users: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to fetch users: ' . $e->getMessage()], 500);
        }
    }


    public function update(Request $request)
    {
        $request->validate([
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_picture' => 'nullable|image|max:10000',
        ]);

        $user = auth()->user();

        try {
            $profile = UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                $request->only(['phone_number', 'address'])
            );

            if ($request->hasFile('profile_picture')) {
                if ($profile->profile_picture) {
                    Storage::disk('public')->delete($profile->profile_picture);
                }

                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                $profile->profile_picture = $path;
                $profile->save();
            }

            $responseData = [
                'id' => $profile->id,
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'phone_number' => $profile->phone_number,
                'address' => $profile->address,
                'role' => $user->role,
                'profile_picture' => $profile->profile_picture ? asset('storage/' . $profile->profile_picture) : null,
                'created_at' => $profile->created_at,
                'updated_at' => $profile->updated_at,
            ];

            Log::info('Profile updated successfully', $responseData);

            return response()->json(['message' => 'Profile updated successfully', 'profile' => $responseData]);
        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to update profile: ' . $e->getMessage()], 500);
        }
    }

    public function sendRekapDataEmail() {
        try {
            Artisan::call('email:send-rekap-data');
            return response()->json(['message' => 'Rekap data email sent successfully']);
        } catch (\Exception $e) {
            Log::error('Error sending rekap data email: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send rekap data email'], 500);
        }
    }

    public function changeUserRole(Request $request, $userId)
{
    if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'super-admin') {
        return response()->json(['error' => 'Unauthorized. Only admin or super admin can change user roles.'], 403);
    }

    $request->validate([
        'role' => 'required|in:user,admin',
    ]);

    try {
        $user = User::findOrFail($userId);

        if ($user->role === 'super-admin') {
            return response()->json(['error' => 'Cannot change role of super-admin'], 403);
        }

        $user->role = $request->role;
        $user->save();

        Log::info('User role updated successfully', [
            'user_id' => $userId,
            'new_role' => $request->role,
            'updated_by' => auth()->user()->id
        ]);

        return response()->json(['message' => 'User role updated successfully', 'user' => $user]);
    } catch (\Exception $e) {
        Log::error('Error changing user role: ' . $e->getMessage(), [
            'user_id' => $userId,
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Failed to change user role: ' . $e->getMessage()], 500);
    }
}


public function deleteUser($userId)
{
    Log::info('Delete user attempt', [
        'user_to_delete' => $userId,
        'requester_id' => auth()->user()->id,
        'requester_role' => auth()->user()->role
    ]);

    if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'super_admin' && auth()->user()->role !== 'super-admin') {
        Log::warning('Unauthorized delete attempt', [
            'requester_id' => auth()->user()->id,
            'requester_role' => auth()->user()->role
        ]);
        return response()->json(['error' => 'Unauthorized. Only admin or super admin can delete user accounts.'], 403);
    }

    try {
        $user = User::findOrFail($userId);
        
        if ($user->role === 'super_admin' || $user->role === 'super-admin') {
            return response()->json(['error' => 'Cannot delete super admin account.'], 403);
        }

        if (auth()->user()->id === $userId) {
            return response()->json(['error' => 'You cannot delete your own account.'], 403);
        }

        // Hapus profile picture jika ada
        if ($user->profile && $user->profile->profile_picture) {
            Storage::disk('public')->delete($user->profile->profile_picture);
        }

        // Hapus profile
        if ($user->profile) {
            $user->profile->delete();
        }

        // Hapus user
        $user->delete();

        Log::info('User deleted successfully', [
            'deleted_user_id' => $userId,
            'deleted_by' => auth()->user()->id
        ]);

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    } catch (\Exception $e) {
        Log::error('Error deleting user: ' . $e->getMessage(), [
            'user_id' => $userId,
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Failed to delete user: ' . $e->getMessage()], 500);
    }
}
}