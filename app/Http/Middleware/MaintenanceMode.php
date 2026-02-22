<?php

namespace App\Http\Middleware;

use App\Models\Configuration;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Configuration::get('maintenance_enabled', '0') !== '1') {
            return $next($request);
        }

        if ($request->is('admin*', 'login*', 'register*', 'logout*', 'forgot-password*', 'reset-password*')) {
            return $next($request);
        }

        $ip        = $request->ip();
        $whitelist = Configuration::get('maintenance_whitelist', '');
        $ips       = array_filter(array_map('trim', explode("\n", $whitelist)));

        if (in_array($ip, $ips)) {
            return $next($request);
        }

        return response(view('mantenimiento'), 503);
    }
}
