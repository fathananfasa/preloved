<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        // Jangan hitung admin/login/dashboard
        if (
            $request->is('admin/*') ||
            $request->is('login') ||
            $request->is('register')
        ) {
            return $next($request);
        }

        $ip = $request->ip();
        $today = now()->toDateString();

        $exists = DB::table('visitors')
            ->where('ip_address', $ip)
            ->where('visit_date', $today)
            ->exists();

        if (!$exists) {
            DB::table('visitors')->insert([
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
                'visit_date' => $today,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $next($request);
    }
}