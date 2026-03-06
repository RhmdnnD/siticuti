<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user yang sedang login BUKAN admin
        if (auth()->check() && auth()->user()->role !== 'admin') {
            
            // Tendang balik ke rute /dashboard, dan kirim pesan error merah
            return redirect('/dashboard')->withErrors(['error' => 'Akses Ditolak! Anda tidak memiliki izin untuk masuk ke Panel Admin.']);
        }

        // Jika dia admin, silakan lewat
        return $next($request);
    }
}