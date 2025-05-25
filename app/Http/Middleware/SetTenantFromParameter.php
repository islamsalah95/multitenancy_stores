<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Store;
use Symfony\Component\HttpFoundation\Response;

class SetTenantFromParameter
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get store from route parameter
        $store = $request->route('store');
        
        if ($store && $store instanceof Store) {
            // Set the tenant context
            $store->makeCurrent();
        }
        
        return $next($request);
    }
}
