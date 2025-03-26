<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $user = $request->user();
        $token = $user->createToken('auth-token');

        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $user,
        ]);
    }


    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'token' => $token,
            'user' => $user
        ], 201);
    }



    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request)
    {
        // Delete the token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }



    // get the authenticated user method
    public function user(Request $request) {

        $userData = new UserResource($request->user());

        return response()->json([
            $userData
        ]);
    }



    public function settings(Request $request) {

        $user = Auth::user();

//        $userPerms = $user->getPermissions();
//
//        $permissionsArray = [];
//        foreach ($userPerms as $key => $val) {
//            $permissionsArray[$val['title']] = $val['allow'];
//        }

        return response()->json([
            'account' => new UserResource($user),
//            'permissions' => $permissionsArray,
        ]);
    }

}
