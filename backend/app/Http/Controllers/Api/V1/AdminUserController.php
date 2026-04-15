<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminUserRequest;
use App\Http\Requests\UpdateAdminUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(): JsonResponse
    {
        return UserResource::collection(
            User::query()
                ->whereIn('role', [UserRole::Admin->value, UserRole::Cashier->value])
                ->orderBy('role')
                ->orderBy('name')
                ->get()
        )->response();
    }

    public function store(StoreAdminUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        $user = User::query()->create($validated);

        return UserResource::make($user)->response()->setStatusCode(201);
    }

    public function update(UpdateAdminUserRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return UserResource::make($user->fresh())->response();
    }

    public function destroy(User $user): JsonResponse
    {
        // Guard: cannot deactivate a superadmin
        if ($user->role?->value === 'superadmin' || $user->role === 'superadmin') {
            return response()->json(['message' => 'Superadmin accounts cannot be deactivated.'], 403);
        }

        $user->update(['is_active' => false]);

        return response()->json(['message' => 'User deactivated.']);
    }
}
