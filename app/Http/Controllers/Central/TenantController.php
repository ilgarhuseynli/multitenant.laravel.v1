<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\CentralUser;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{

    public function index(){

        $tenants = Tenant::all();

        return response()->json($tenants);
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        try {
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

        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Tenant created',
            'data' => [
                'tenant' => $tenant,
                'user' => $user,
            ]
        ]);
    }

    public function show($id){
        $tenant = Tenant::where('id', $id)->firstOrFail();

        return response()->json($tenant);
    }

    public function update($id){

        $tenant = Tenant::where('id', $id)->firstOrFail();

        return response()->json($tenant);
    }


    public function destroy($tenantId){

        $tenant = Tenant::where('id', $tenantId)->firstOrFail();

        $tenant->delete();

        return response()->json(['message' => 'Tenant deleted']);
    }
}
