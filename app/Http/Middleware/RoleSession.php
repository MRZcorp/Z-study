<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(
        Request $request,
        Closure $next,
        string $role   // ✅ WAJIB ADA
    ): Response
    { 
        // dd($role);
        if (!session()->has('nama_role')) {
            abort(403, 'Role tidak ditemukan');
        }

        if (strtolower(session('nama_role')) !== strtolower($role)) {
            abort(403, 'Anda tidak punya akses ke halaman ini');
        }

        return $next($request);
    }
        
}
