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
            'tenant_id' => 'required|string|exists:tenants,id'
        ]);

        $user = $request->user();
        $tenant = Tenant::find($request->tenant_id);
        
        // Revoke current token
        $user->currentAccessToken()->delete();
        
        // Create new token with the selected tenant
        $token = $user->createTokenWithTenant('auth_token', $tenant->id);
        
        // Initialize tenancy for the current request
        tenancy()->initialize($tenant);
        
        return response()->json([
            'message' => 'Tenant selected successfully',
            'token' => $token->plainTextToken, 
            'tenant' => $tenant
        ]);
    }

    public function getCurrentTenant(Request $request): JsonResponse
    {
        $user = $request->user();
        $metadata = json_decode($user->currentAccessToken()->metadata ?? '{}', true);
        $tenantId = $metadata['tenant_id'] ?? null;
        
        if (!$tenantId) {
            return response()->json(['message' => 'No tenant associated with this token'], 404);
        }
        
        $tenant = Tenant::find($tenantId);
        
        return response()->json([
            'tenant' => $tenant
        ]);
    }

    public function getAvailableTenants(string $email): JsonResponse
    {
        $tenants = CentralUser::where('email', $email)
            ->with('tenant')
            ->get()
            ->pluck('tenant');
            
        return response()->json([
            'tenants' => $tenants
        ]);
    }
} 