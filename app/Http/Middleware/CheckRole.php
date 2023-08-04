<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $role = strtolower(request()->user()->type);
        $allowed_roles = array_slice(func_get_args(), 2);

        if (in_array($role, $allowed_roles)) {
            return $next($request);
        }

        throw new AuthenticationException("User With {$role} Role Can't Access this Page.");
    }
}
