<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * @param  array<int, string>  $roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        $role = $user->role instanceof UserRole ? $user->role->value : $user->role;

        if (! in_array($role, $roles, true)) {
            abort(403, 'You do not have access to this resource.');
        }

        return $next($request);
    }
}
