<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->isAllowedRole($user, [UserRole::Cashier, UserRole::Admin, UserRole::Superadmin]);
    }

    public function view(User $user, Order $order): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
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
