<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CentralUser;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'tenant_id' => 'nullable|string|exists:tenants,id'
        ]);

        // Find all tenants where this user exists
        $tenants = CentralUser::where('email', $request->email)
            ->with('tenant')
            ->get();

        if ($tenants->isEmpty()) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // If tenant_id is not provided and user exists in multiple tenants
        if (!$request->tenant_id && $tenants->count() > 1) {
            return response()->json([
                'message' => 'Multiple tenants found',
                'tenants' => $tenants->pluck('tenant'),
                'requires_selection' => true
            ]);
        }

        // Get the tenant (either from request or the only one available)
        $tenant = $request->tenant_id
            ? Tenant::find($request->tenant_id)
            : $tenants->first()->tenant;

        // Initialize tenancy for the selected tenant
        tenancy()->initialize($tenant);


        // Authenticate user
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;


        // Attempt authentication in the tenant's context
//        $user = User::where('email', $request->email)->first();
//
//        if (!$user || !Hash::check($request->password, $user->password)) {
//            throw ValidationException::withMessages([
//                'email' => ['The provided credentials are incorrect.'],
//            ]);
//        }

        // Create token with tenant ID embedded
        $token = $user->createTokenWithTenant('auth_token', $tenant->id);

        return response()->json([
            'message' => 'Logged in successfully',
            'token' => $token->plainTextToken,
            'user' => $user,
            'tenant' => $tenant
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        // Revoke the token that was used to authenticate the current request
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
}
