<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class TenantController extends Controller
{

    public function index(){

    }
    public function store(Request $request){

        $tenantName = 'foo';

        try {
            $tenant = Tenant::create([
                'id' => $tenantName
            ]);

            $tenant->domains()->create([
                'domain' => $tenantName.'.localhost',
            ]);

            Tenant::all()->runForEach(function () {
                User::factory()->create();
            });
        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()], 500);
        }

        return response()->json(['message' => 'Tenant created']);
    }

    public function show($id){

    }

    public function update($id){

    }

    public function destroy($tenantId){

        $tenant = Tenant::where('id', $tenantId)->firstOrFail();

        $tenant->delete();

        return response()->json(['message' => 'Tenant deleted']);
    }
}
