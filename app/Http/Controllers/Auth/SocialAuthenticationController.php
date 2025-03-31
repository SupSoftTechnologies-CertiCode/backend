<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
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
    
            // Find or create user
            $user = User::updateOrCreate([
                'email' => $socialUser->email, // Ensure the email check to avoid duplicates
            ], [
                'name' => $socialUser->name,
                'auth_provider_id' => $socialUser->id,
                'auth_provider' => $provider,
                'email_verified_at' => $socialUser->email_verified ? now() : null,
            ]);
    
            // Ensure user profile exists
            $userProfile = UserProfile::firstOrCreate([
                'users_id' => $user->id,
            ]);
    
            // Load the profile into the user object
            $user->load('userProfile');
    
            // Generate JWT token
            $token = JWTAuth::fromUser($user);
    
            // Redirect to frontend with token and user data
            $redirectUrl = env('FRONTEND_URL') . "/social-auth-handler?token=" . $token . "&user=" . urlencode(json_encode($user));
    
            return redirect($redirectUrl);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Authentication failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    

}
    
            

        
