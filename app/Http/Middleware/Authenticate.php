<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        // do not redirect API requests to the login page
        if ($request->is('api/*') || $request->expectsJson()) {
            return null;
        }

        return route('login');
    }
}
