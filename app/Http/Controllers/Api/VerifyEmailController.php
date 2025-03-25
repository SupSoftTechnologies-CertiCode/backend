<?php

// namespace App\Http\Controllers\Api;

// use Illuminate\Http\Request;
// use Illuminate\Auth\Events\Verified;
// use Illuminate\Routing\Controller;
// use App\Models\User;

// namespace App\Http\Controllers\Api;

// use Illuminate\Http\Request;
// use Illuminate\Auth\Events\Verified;
// use App\Http\Controllers\Controller;
// use App\Models\User;
// use Illuminate\Support\Facades\Log;

// class VerifyEmailController extends Controller
// {
//     public function __invoke(Request $request, $id, $hash)
//     {
//         Log::info('VerifyEmailController invoked', ['id' => $id, 'hash' => $hash]);

//     $user = User::find($id);

//     if (!$user) {
//         Log::error('User not found', ['id' => $id]);
//         return response()->json(['message' => 'User not found'], 404);
//     }
//         $user = User::find($id);

//         if (!$user) {
//             return response()->json(['message' => 'User not found'], 404);
//         }

//         // Verify the hash
//         if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
//             return response()->json(['message' => 'Invalid verification link'], 400);
//         }

//         // Check if the email is already verified
//         if ($user->hasVerifiedEmail()) {
//             return response()->json(['message' => 'Email already verified'], 400);
//         }

//         // Mark the email as verified
//         if ($user->markEmailAsVerified()) {
//             event(new Verified($user));
//         }

//         return response()->json(['message' => 'Email verified successfully']);
//     }
//  }

// namespace App\Http\Controllers\Api;

// use Illuminate\Http\Request;
// use Illuminate\Auth\Events\Verified;
// use App\Http\Controllers\Controller;
// use App\Models\User;
// use Illuminate\Support\Facades\Log;

// class VerifyEmailController extends Controller
// {
//     public function __invoke(Request $request, $id, $hash)
//     {
//         Log::info('VerifyEmailController invoked', ['id' => $id, 'hash' => $hash]);

//         $user = User::find($id);

//         if (!$user) {
//             Log::error('User not found', ['id' => $id]);
//             return response()->json(['message' => 'User not found'], 404);
//         }

//         // Verify the hash
//         if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
//             return response()->json(['message' => 'Invalid verification link'], 400);
//         }

//         // Check if the email is already verified
//         if ($user->hasVerifiedEmail()) {
//             return redirect(env('FRONTEND_URL') ."/dashboard");
//         }

//         // Mark the email as verified
//         if ($user->markEmailAsVerified()) {
//             event(new Verified($user));
//         }

//         return redirect(env('FRONTEND_URL') ."/dashboard");
//     }
// }

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request, $id, $hash)
    {
        Log::info('VerifyEmailController invoked', ['id' => $id, 'hash' => $hash]);

        $user = User::find($id);

        $current_user = UserProfile::with(['users'])->where('users_id', $id)->first();

        if (!$user) {
            Log::error('User not found', ['id' => $id]);
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            $token = JWTAuth::fromUser($user);
            return response()->json([
                'message' => 'Email successfully verified',
                'token' => $token,  
                'redirect_url' => env('FRONTEND_URL') . "/verify-handler?token=" . $token . "&redirect_url=" . urlencode(env('FRONTEND_URL') . "/dashboard"),
                'current_user' => $current_user
            ]);
        }

       
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Email successfully verified',
            'token' => $token,
            'redirect_url' => env('FRONTEND_URL') . "/verify-handler?token=" . $token . "&redirect_url=" . urlencode(env('FRONTEND_URL') . "/dashboard"),
            'current_user' => $current_user
        ]);

    }
}