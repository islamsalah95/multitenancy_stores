<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Store;
use Symfony\Component\HttpFoundation\Response;

class DetectTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        
        // Skip tenant detection for main domains
        if ($this->isLandlordDomain($host)) {
            return $next($request);
        }
        
        // Try to find tenant by domain
        $store = Store::where('domain', $host)
            ->where('is_active', true)
            ->first();
            
        if (!$store) {
            // If no tenant found and this looks like a tenant domain, show 404
            if ($this->looksTenantDomain($host)) {
                abort(404, 'Store not found');
            }
            // Otherwise continue as landlord
            return $next($request);
        }
        
        // Set the tenant
        $store->makeCurrent();
        
        return $next($request);
    }
    
    private function isLandlordDomain(string $host): bool
    {
        $landlordDomains = [
            'localhost',
            '127.0.0.1',
            'localhost:8000',
            '127.0.0.1:8000',
        ];
        
        return in_array($host, $landlordDomains);
    }
    
    private function looksTenantDomain(string $host): bool
    {
        // Check if it looks like a tenant domain (contains subdomain or custom domain)
        return str_contains($host, '.localhost') || 
               (!str_contains($host, 'localhost') && !str_contains($host, '127.0.0.1'));
    }
}
