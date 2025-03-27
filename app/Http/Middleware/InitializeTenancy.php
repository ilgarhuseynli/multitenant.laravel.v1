<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenancy
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user || !$user->currentAccessToken()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        // Extract tenant ID from token metadata
        $metadata = json_decode($user->currentAccessToken()->metadata ?? '{}', true);
        $tenantId = $metadata['tenant_id'] ?? null;
        
        if (!$tenantId) {
            return response()->json(['message' => 'No tenant associated with this token'], 401);
        }
        
        $tenant = Tenant::find($tenantId);
        
        if (!$tenant) {
            return response()->json(['message' => 'Invalid tenant'], 401);
        }
        
        // Initialize tenancy for the request
        tenancy()->initialize($tenant);
        
        return $next($request);
    }
} 