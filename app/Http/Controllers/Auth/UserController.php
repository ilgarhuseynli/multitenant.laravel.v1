<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
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
