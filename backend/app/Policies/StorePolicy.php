<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Store;
use App\Models\User;

class StorePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->isSuperadmin($user);
    }

    public function view(User $user, Store $store): bool
    {
        return $this->isSuperadmin($user);
    }

    public function create(User $user): bool
    {
        return $this->isSuperadmin($user);
    }

    public function update(User $user, Store $store): bool
    {
        return $this->isSuperadmin($user);
    }

    private function isSuperadmin(User $user): bool
    {
        $role = $user->role?->value ?? $user->role;

        return $role === UserRole::Superadmin->value;
    }
}
