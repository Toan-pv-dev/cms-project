<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class setDefaultLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $locale = Session::get('app_locale', config('app.locale'));
        dd($locale);
        App::setLocale($locale);
        if (!Session::has('app_locale')) {
            Session::put('app_locale', $locale);
        }



        return $next($request);
    }
}
