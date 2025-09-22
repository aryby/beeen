<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\DynamicConfigService;
use Symfony\Component\HttpFoundation\Response;

class DynamicConfigMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Configurer dynamiquement SMTP et PayPal à chaque requête
        // Cela garantit que les configurations de la base de données sont toujours utilisées
        DynamicConfigService::configureAll();

        return $next($request);
    }
}
