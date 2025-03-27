<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CentralUser;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function index(Request $request): JsonResponse
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

        return response()->json([
            'message' => 'Logged in successfully',
            'token' => $token,
            'user' => $user,
            'tenant' => $tenant
        ]);
    }

}
