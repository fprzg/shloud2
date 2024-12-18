<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
//use Symfony\Component\HttpFoundation\Response;

use Illuminate\Auth\Middleware\Authenticate as MiddlewareAuth;

class RedirectNotAuthMiddleware extends MiddlewareAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$guards): Response
    {
        $this->authenticate($request, $guards);

        return $next($request);
    }

    /**
     * Determine if the users is logged in to any of the given guards.
     * 
     * @param \Illuminate\Http\Request $request
     * @param array @guards
     * @return void
     * 
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function redirectTo(Request $request): ?string
    {
        if(empty($guards)) {
            $guards = [null];
        }

        foreach($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        /*
        if (! $request->expectsJson()) {
            throw new AuthenticateException('Unauthenticated', $guards);
        }
            */
        return redirect()->route('users.login');
    }
}
