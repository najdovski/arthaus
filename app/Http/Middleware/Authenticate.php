<?php

namespace App\Http\Middleware;
use App\Helpers\AccessTokenHelper;
use Closure;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    // Overriding the handle method for allowing access with token
    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->auth->check() || AccessTokenHelper::checkToken($request)) {
            return $next($request);
        } else {
            return redirect()->guest('login');
        }
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
