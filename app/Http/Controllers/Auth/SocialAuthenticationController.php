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

            $user = User::updateOrCreate([
                'auth_provider_id' => $socialUser->id,
            ], [
                'name' => $socialUser->name,
                'email' => $socialUser->email,
                'auth_provider_id' => $socialUser->id,
                'auth_provider' => $provider,
                'email_verified_at' => $socialUser->email_verified ? now() : null,
            ]);

                $token = JWTAuth::fromUser($user);

                $redirectUrl = env('FRONTEND_URL') . "/social-auth-handler?token=" . $token;
        
                return redirect($redirectUrl);
            } catch (Exception $e) {
                return response()->json(['status' => 'error', 'message' => 'Authentication failed'], 500);
        }
    }

}
    
            

        
