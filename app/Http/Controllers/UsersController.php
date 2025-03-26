<?php

namespace App\Http\Controllers;

use App\Classes\Helpers;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserMinlistResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UsersController extends Controller
{

    public function __construct()
    {
        Gate::authorize('user_access');
    }

    public function index(Request $request)
    {
        Gate::authorize('user_show');

        $limit = Helpers::manageLimitRequest($request->limit);
        $sort = Helpers::manageSortRequest($request->sort,$request->sort_type,User::$sortable);

        $users = User::query()
            ->filter($request->only(['name', 'email'])) // Apply filters scope
            ->orderBy($sort['field'], $sort['direction'])
            ->paginate($limit);

        return UserResource::collection($users);
    }

    public function minlist(Request $request)
    {
        $limit = Helpers::manageLimitRequest($request->limit);
        $sort = Helpers::manageSortRequest($request->sort,$request->sort_type,User::$sortable);

        $users = User::query()
            ->filter($request->only(['name', 'email'])) // Apply filters scope
            ->orderBy($sort['field'], $sort['direction'])
            ->simplePaginate($limit);

        return UserMinlistResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $validUserFields = $request->validated();

        $user = User::create($validUserFields);

        return response()->json(['id' => $user->id]);
    }

    public function show(User $user)
    {
        Gate::authorize('user_show',$user);

        return response()->json(new UserResource($user));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validUserFields = $request->validated();

        $user->update($validUserFields);

        return response()->json(['id' => $user->id]);
    }

    public function updatePassword(Request $request,User $user)
    {
        Gate::authorize('user_edit',$user);

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['id' => $user->id]);
    }

    public function destroy(User $user)
    {
        Gate::authorize('user_delete',$user);

        $user->delete();

        return response()->noContent();
    }


}
