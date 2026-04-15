<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->isAllowedRole($user, [UserRole::Cashier, UserRole::Admin, UserRole::Superadmin]);
    }

    public function view(User $user, Product $product): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->isAllowedRole($user, [UserRole::Admin, UserRole::Superadmin]);
    }

    public function update(User $user, Product $product): bool
    {
        return $this->create($user);
    }

    /**
     * @param array<int, UserRole> $roles
     */
    private function isAllowedRole(User $user, array $roles): bool
    {
        $role = $user->role?->value ?? $user->role;

        return in_array($role, array_map(static fn (UserRole $item) => $item->value, $roles), true);
    }
}
