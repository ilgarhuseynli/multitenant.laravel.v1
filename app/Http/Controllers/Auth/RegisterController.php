<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CentralUser;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => [
                'required',
                'confirmed',
//                Rules\Password::defaults(),
            ],
        ]);


        try {

            // Find all tenants where this user exists
            $tenant = Tenant::create();

            $tenant->domains()->create([
                'domain' => $tenant->id.'.localhost',
            ]);

            CentralUser::create([
                'email' => $request->email,
                'tenant_id' => $tenant->id,
            ]);

            tenancy()->initialize($tenant);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->string('password')),
            ]);

            // Create token with tenant ID embedded
            $token = $user->createToken('auth-token')->plainTextToken;


        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()], 500);
        }


        return response()->json([
            'message' => 'Logged in successfully',
            'token' => $token,
            'user' => $user,
            'tenant' => $tenant
        ]);
    }

}
