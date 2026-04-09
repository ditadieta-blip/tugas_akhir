<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            abort(403, 'Belum login');
        }

        $user = auth()->user();

        if (!$user->role) {
            abort(403, 'User tidak punya role');
        }

        $userRole = strtolower(trim($user->role->nama_role));

        foreach ($roles as $role) {
            if ($userRole === strtolower(trim($role))) {
                return $next($request);
            }
        }

        abort(403, 'Akses ditolak');
    }
}