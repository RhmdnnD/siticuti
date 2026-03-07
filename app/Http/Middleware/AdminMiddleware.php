<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role !== 'admin') {
            
            return redirect('/dashboard')->withErrors(['error' => 'Akses Ditolak! Anda tidak memiliki izin untuk masuk ke Panel Admin.']);
        }

        return $next($request);
    }
}