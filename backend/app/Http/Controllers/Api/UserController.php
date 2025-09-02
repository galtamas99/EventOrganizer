<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $size  = (int) $request->query('size', 10);
        $sort  = $request->query('sort', 'created_at');
        $order = $request->query('order', 'desc');

        $q = User::query()->with('roles')->orderBy($sort, $order);

        if ($search = $request->query('search')) {
            $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $q->paginate($size);

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest  $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'], 
        ]);

        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        } else {
            $user->syncRoles(['user']);
        }

        return (new UserResource($user->load('roles')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user->load('roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if (array_key_exists('name', $data))     $user->name = $data['name'];
        if (array_key_exists('email', $data))    $user->email = $data['email'];
        if (array_key_exists('password', $data) && $data['password']) {
            $user->password = $data['password'];
        }

        $user->save();

        if (array_key_exists('roles', $data)) {
            $user->syncRoles($data['roles'] ?? []);
        }

        return new UserResource($user->load('roles'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return response()->json([
                'message' => 'You cannot delete your own user.'
            ], 422);
        }

        $user->delete();

        return response()->noContent();
    }
}
