<?php

namespace App\TenantFinder;

use App\Models\Store;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class DomainTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?IsTenant
    {
        $host = $request->getHost();

        // Remove port if present
        $domain = explode(':', $host)[0];

        // Find store by domain
        $store = Store::where('domain', $domain)
            ->where('is_active', true)
            ->first();

        return $store;
    }
}
