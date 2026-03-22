<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! $user->hasRole(Role::Company) || ! $user->company) {
            return response()->json(['message' => 'Accès réservé aux entreprises.'], 403);
        }

        if ($user->company->approval_status !== 'approved') {
            return response()->json(['message' => 'Votre entreprise doit être approuvée par un administrateur.'], 403);
        }

        return $next($request);
    }
}
