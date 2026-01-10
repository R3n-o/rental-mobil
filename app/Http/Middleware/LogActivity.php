<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Biarkan request diproses dulu sampai selesai
        $response = $next($request);

        // 2. Setelah selesai, cek apakah request ini mengubah data?
        // Kita hanya log method: POST, PUT, DELETE
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE'])) {
            
            // Cek apakah user login?
            $user = auth()->guard('api')->user();

            ActivityLog::create([
                'user_id'    => $user ? $user->id : null, // Bisa null jika register/login
                'action'     => $this->determineAction($request),
                'url'        => $request->fullUrl(),
                'method'     => $request->method(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
        }

        return $response;
    }

    // Helper kecil untuk menamai aksi biar rapi di database
    private function determineAction($request)
    {
        // Contoh URL: http://localhost/api/login -> Action: LOGIN
        if ($request->is('api/login')) return 'LOGIN';
        if ($request->is('api/register')) return 'REGISTER';
        if ($request->is('api/logout')) return 'LOGOUT';
        
        // Contoh: POST /api/bookings -> Action: CREATE DATA
        if ($request->method() == 'POST') return 'CREATE DATA';
        if ($request->method() == 'PUT') return 'UPDATE DATA';
        if ($request->method() == 'DELETE') return 'DELETE DATA';

        return 'UNKNOWN';
    }
}