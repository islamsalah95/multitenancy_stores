<?php

namespace App\Console\Commands;

use App\Models\Store;
use App\Services\TenantDatabaseService;
use Illuminate\Console\Command;

class TenantMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate {store?} {--fresh : Drop all tables and re-run all migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations for tenant(s)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantService = app(TenantDatabaseService::class);
        
        $storeId = $this->argument('store');
        
        if ($storeId) {
            // Migrate specific store
            $store = Store::find($storeId);
            if (!$store) {
                $this->error("Store with ID {$storeId} not found.");
                return 1;
            }
            
            $this->info("Running migrations for store: {$store->name}");
            $this->migrateTenant($store, $tenantService);
        } else {
            // Migrate all stores
            $stores = Store::where('is_active', true)->get();
            
            if ($stores->isEmpty()) {
                $this->info('No active stores found.');
                return 0;
            }
            
            $this->info("Running migrations for {$stores->count()} store(s)...");
            
            foreach ($stores as $store) {
                $this->info("Migrating: {$store->name}");
                $this->migrateTenant($store, $tenantService);
            }
        }
        
        $this->info('Tenant migrations completed!');
        return 0;
    }
    
    private function migrateTenant(Store $store, TenantDatabaseService $tenantService)
    {
        try {
            if ($this->option('fresh')) {
                // Drop and recreate database
                $tenantService->dropDatabase($store->getDatabaseName());
                $tenantService->createDatabase($store->getDatabaseName());
            }
            
            $tenantService->runMigrations($store);
            $this->line("✓ {$store->name} migrated successfully");
        } catch (\Exception $e) {
            $this->error("✗ Failed to migrate {$store->name}: " . $e->getMessage());
        }
    }
}
