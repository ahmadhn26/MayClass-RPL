<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  array<int, string>  $roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): SymfonyResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($roles && ! in_array($user->role, $roles, true)) {
            abort(SymfonyResponse::HTTP_FORBIDDEN, __('Anda tidak memiliki akses ke halaman ini.'));
        }

        return $next($request);
    }
}
