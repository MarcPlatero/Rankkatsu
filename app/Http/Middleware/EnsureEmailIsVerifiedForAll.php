<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerifiedForAll
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si no està autenticat, no fem res
        if (!$request->user()) {
            return $next($request);
        }

        // Si ja està verificat, cap problema
        if ($request->user()->hasVerifiedEmail()) {
            return $next($request);
        }

        // Permetem algunes rutes encara que no estigui verificat
        if ($request->routeIs('verification.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        // Redirigim tot el demés a la verificació
        return redirect()->route('verification.notice');
    }
}
