<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    public function handle(Request $request, Closure $next, $userType): Response
    {
        if (Auth::check()) {
            if (Auth::user()->type === $userType) {
                return $next($request);
            }
        }

        return redirect()->route('login'); // Redirect ke halaman login jika tidak sesuai
    }
}
