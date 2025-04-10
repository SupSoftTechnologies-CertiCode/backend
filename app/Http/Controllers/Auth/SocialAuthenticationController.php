<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialAuthenticationController extends Controller
{

    public function authProvideRedirection($provider) {        
        if($provider) {         
            return Socialite::driver($provider)->redirect();        
        }     
    }

    public function socialAuthentication($provider) {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
    

            $nameParts = explode(' ', $socialUser->name, 2);
            $firstName = $nameParts[0] ?? null;
            $lastName = $nameParts[1] ?? null;
            // Find or create user
            $user = User::updateOrCreate([
                'email' => $socialUser->email, // Ensure the email check to avoid duplicates
            ], [
                'name' => $socialUser->name,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'auth_provider_id' => $socialUser->id,
                'auth_provider' => $provider,
                'email_verified_at' => $socialUser->email_verified ? now() : null,
            ]);
    

            // Generate JWT token
            $token = JWTAuth::fromUser($user);
    
            // Redirect to frontend with token
            $redirectUrl = env('FRONTEND_URL') . "/social-auth-handler?token=" . $token;
    
            return redirect($redirectUrl);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Authentication failed',
                'error' => $e->getMessage() // Optional: Include for debugging
            ], 500);
        }
    }
    

}
    
            

        
