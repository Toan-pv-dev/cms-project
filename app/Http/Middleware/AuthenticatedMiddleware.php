<?php

// namespace App\Http\Controllers\Backend;

// use Illuminate\Http\Request;

namespace App\Http\Middleware;
// use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::id() == null) {
            flash()->error('Ban can dang nhap de thuc hien chuc nang nay');
            return redirect()->route('auth.admin');
        }
        return $next($request);
    }
}
