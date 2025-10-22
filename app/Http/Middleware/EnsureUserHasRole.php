<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request and verify the authenticated user has one of the expected roles.
     *
    * Usage in routes: ->middleware('role:admin,warehouse_manager,guard=web') or ->middleware('role:admin|warehouse_manager')
     */
    public function handle(Request $request, Closure $next, ...$parameters): Response
    {
        [$roles, $guard] = $this->parseParameters($parameters);

        $auth = auth($guard);

        if (! $auth->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED);
            }

            abort(Response::HTTP_UNAUTHORIZED, 'Silakan login terlebih dahulu.');
        }

        /** @var \Illuminate\Contracts\Auth\Authenticatable|\App\Models\User $user */
        $user = $auth->user();

        if ($roles) {
            $hasRole = false;

            if (method_exists($user, 'hasAnyRole')) {
                $hasRole = $user->hasAnyRole($roles);
            } else {
                $hasRole = in_array($user->role ?? null, $roles, true);
            }

            if (! $hasRole) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Forbidden.',
                        'allowed_roles' => $roles,
                    ], Response::HTTP_FORBIDDEN);
                }

                abort(Response::HTTP_FORBIDDEN, 'Anda tidak memiliki hak akses untuk halaman ini.');
            }
        }

        // share guard information for downstream usage (logging, views, etc.)
        $request->attributes->set('auth_guard', $guard ?? config('auth.defaults.guard'));

        return $next($request);
    }

    /**
     * Split incoming middleware parameters into roles array and optional guard name.
     */
    protected function parseParameters(array $parameters): array
    {
        $roles = [];
        $guard = null;

        foreach ($parameters as $parameter) {
            if (str_starts_with($parameter, 'guard=')) {
                $guard = trim(substr($parameter, 6));
                continue;
            }

            $chunk = array_filter(array_map('trim', explode('|', $parameter)));
            $roles = array_merge($roles, $chunk);
        }

        return [Arr::where($roles, fn ($role) => $role !== ''), $guard];
    }
}
