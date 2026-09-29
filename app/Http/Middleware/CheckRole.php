<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();
        if (!$user || !in_array((int) $user->id_role, array_map('intval', $roles))) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
