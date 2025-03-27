<?php

namespace App\Http\Controllers;

use App\Models\CentralUser;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantSessionController extends Controller
{
    public function selectTenant(Request $request): JsonResponse
    {
        $request->validate([
            'tenant_id' => 'required|string'
        ]);

        $user = $request->user();

        // Find all tenants where this user exists
        CentralUser::where('email', $user->email)
            ->where('tenant_id', $request->tenant_id)
            ->firstOrFail();

        $tenant = Tenant::findOrFail($request->tenant_id);

        // Revoke current token
        $user->currentAccessToken()->delete();

        // Initialize tenancy for the current request
        tenancy()->initialize($tenant);

        // Create new token with the selected tenant
        $token = $user->createToken('auth-token')->plainTextToken;


        return response()->json([
            'message' => 'Tenant selected successfully',
            'token' => $token,
            'tenant' => $tenant
        ]);
    }

    public function getCurrentTenant(Request $request): JsonResponse
    {
        // Retrieve tenant ID from request header
        $tenantId = $request->header('X-Tenant');

        if (!$tenantId) {
            return response()->json(['message' => 'Tenant ID is missing'], 400);
        }

        // Find tenant
        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        return response()->json([
            'tenant' => $tenant
        ]);
    }

    public function getAvailableTenants(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        $tenants = CentralUser::where('email', $request->email)
            ->with('tenant')
            ->get()
            ->pluck('tenant');

        return response()->json($tenants);
    }
}
