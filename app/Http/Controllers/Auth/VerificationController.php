<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Access\AuthorizationException;

class VerificationController extends Controller
{

    use VerifiesEmails;

    protected $redirectTo = '/home';

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('signed') -> only('verify');
        $this->middleware('throttle:6,1') -> only('verify', 'resend');
    }
    public function verify($id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException('Invalid verification link');
        }

        if ($user->hasVerifiedEmail()) {
            return view('auth.verification-success')->with('message', 'Email already verified');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        
        return view('auth.verification-success')->with('message', 'Email verified successfully');
    }
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent'], 200);
    }
}
