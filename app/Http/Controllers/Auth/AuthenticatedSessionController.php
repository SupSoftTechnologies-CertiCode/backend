<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request) 
    // :Response
    {
        $current_user = User::where('email', $request->email)->first();
        $request->authenticate();

        $request->session()->regenerate();
        $isVerified = $current_user->hasVerifiedEmail();

        return response()->json([
            'message'=>"Login Success", 
            'user'=>$current_user,
            'isVerified' => $isVerified,
        ],200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
