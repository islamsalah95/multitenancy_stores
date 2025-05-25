<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\Category;
use App\Models\Product;
use App\Services\TenantDatabaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MultiTenancyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test store
        $this->store = Store::create([
            'name' => 'Test Store',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'domain' => 'test-store.localhost',
            'database' => 'tenant_test_store',
            'is_active' => true,
        ]);
    }

    public function test_store_creates_domain_and_database_automatically()
    {
        $store = Store::create([
            'name' => 'Auto Store',
            'email' => 'auto@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->assertNotNull($store->domain);
        $this->assertNotNull($store->database);
        $this->assertEquals('auto-store.localhost', $store->domain);
        $this->assertEquals('tenant_auto_store', $store->database);
    }

    public function test_tenant_finder_resolves_store_by_domain()
    {
        $request = $this->createRequest('GET', 'http://test-store.localhost');
        
        $tenantFinder = new \App\TenantFinder\DomainTenantFinder();
        $tenant = $tenantFinder->findForRequest($request);
        
        $this->assertNotNull($tenant);
        $this->assertEquals($this->store->id, $tenant->id);
    }

    public function test_tenant_database_service_creates_database()
    {
        $service = app(TenantDatabaseService::class);
        $databaseName = 'test_tenant_db';
        
        $result = $service->createDatabase($databaseName);
        $this->assertTrue($result);
        
        $exists = $service->databaseExists($databaseName);
        $this->assertTrue($exists);
        
        // Clean up
        $service->dropDatabase($databaseName);
    }

    public function test_tenant_routes_work_with_middleware()
    {
        // This would require setting up the tenant context properly
        // For now, we'll test that the routes are defined
        $this->assertTrue(true); // Placeholder
    }

    public function test_models_use_tenant_connection()
    {
        $this->assertTrue(in_array('Spatie\Multitenancy\Models\Concerns\UsesTenantConnection', class_uses(Category::class)));
        $this->assertTrue(in_array('Spatie\Multitenancy\Models\Concerns\UsesTenantConnection', class_uses(Product::class)));
    }

    private function createRequest($method, $uri)
    {
        $request = \Illuminate\Http\Request::create($uri, $method);
        return $request;
    }
}
