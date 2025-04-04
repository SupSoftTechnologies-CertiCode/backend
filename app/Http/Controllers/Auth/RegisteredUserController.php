<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'age' => ['required', 'integer', 'min:0' ,'max:255'],
            'gender' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'min:11' ,'max:11'],
            'address' => ['required', 'string', 'max:255']
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->first_name . " " . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'age' => $request->age,
                'gender' => $request->gender,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);
    
            UserProfile::create([
                'users_id' => $user->id,
                
            ]);
    
            event(new Registered($user));
    
            // Auth::login($user);
            DB::commit();
            return response()->json(['message'=>"Register Success",
        ],200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message'=>'Error', 'error' => $e->getMessage()], 500);
        }
       
    } 
    public function update(Request $request)
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        // Validate request data
        $validatedData = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0|max:255',
            'gender' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'phone' => 'nullable|string|min:11|max:11',
        ]);
    
        DB::beginTransaction();
        try {
            // Concatenate first_name and last_name for the name column
            $validatedData['name'] = trim(($validatedData['first_name'] ?? $user->first_name) . ' ' . ($validatedData['last_name'] ?? $user->last_name));
    
            // Update user record directly
            $user->update($validatedData);
    
            DB::commit();
    
            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error updating profile', 'error' => $e->getMessage()], 500);
        }
    }
    



}
