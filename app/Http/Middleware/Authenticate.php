<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
   protected function redirectTo(Request $request): ?string{
    if ($request->expectsJson()) {
        return null;
    }

    //  Ne pas écraser url.intended si déjà défini manuellement (ex: depuis store())
    if (!session()->has('url.intended') && !$request->routeIs('login')) {
        session()->put('url.intended', $request->url());
    }

    return route('login');
}
}