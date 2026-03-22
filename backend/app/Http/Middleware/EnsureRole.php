<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * @param  string  ...$roles  Valeurs attendues: admin, student, company, supervisor
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Non authentifié.'], 401);
        }

        $current = $user->role instanceof Role ? $user->role->value : (string) $user->role;
        if (! in_array($current, $roles, true)) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        return $next($request);
    }
}
