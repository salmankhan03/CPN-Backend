<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   

        $domain = parse_url($_SERVER['HTTP_REFERER']);
        $host = '*';
        if (isset($domain['host'])) {
            $host = $domain['host'];
        }

        return $next($request)
        ->header('Access-Control-Allow-Origin', $host)
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Credentials', true)
        ->header('Access-Control-Allow-Headers', 'X-Requested-With,Content-Type,X-Token-Auth,Authorization')
        ->header('Accept', 'application/json');
    }
}
