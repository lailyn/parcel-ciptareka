<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckToken
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah token aktif atau tidak
        if (!auth('sanctum')->check()) {            
            return response()->json(['status' => 0, 'message' => 'Gagal'], 401);
        }
        
        return $next($request);
    }
}
