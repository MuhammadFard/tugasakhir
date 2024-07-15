<?php

namespace App\Http\Controllers;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Mail\DataInputNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $profile = $user->profile;

        if (!$profile) {
            $profile = new UserProfile();
            $profile->user_id = $user->id;
            $profile->save();
        }

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
            'updated_at' => $profile->updated_at
        ];

        Log::info('User profile response:', $response);

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $request->validate([
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_picture' => 'nullable|image|max:10000',
        ]);
    
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    
        $validatedData = $request->only(['phone_number', 'address']);
    
        try {
            $profile = UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                $validatedData
            );
    
            if ($request->hasFile('profile_picture')) {
                if ($profile->profile_picture) {
                    Storage::disk('public')->delete($profile->profile_picture);
                }
    
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                $profile->profile_picture = $path;
                $profile->save();
            }
    
            $fullProfile = array_merge($profile->toArray(), [
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'profile_picture' => $profile->profile_picture ? asset('storage/' . $profile->profile_picture) : null,
            ]);
    
            Log::info('Profile updated successfully', $fullProfile);
    
            return response()->json(['message' => 'Profile updated successfully', 'profile' => $fullProfile]);
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
}