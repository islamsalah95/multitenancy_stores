<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class TenantDatabaseService
{
    /**
     * Create a new tenant database
     */
    public function createDatabase(string $databaseName): bool
    {
        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to create database: {$databaseName}", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Drop a tenant database
     */
    public function dropDatabase(string $databaseName): bool
    {
        try {
            DB::statement("DROP DATABASE IF EXISTS `{$databaseName}`");
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to drop database: {$databaseName}", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Run migrations for a tenant
     */
    public function runMigrations(Store $store): bool
    {
        try {
            // Set the tenant database connection
            $this->setTenantDatabaseConnection($store);
            
            // Run tenant-specific migrations
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);
            
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to run migrations for tenant: {$store->getDatabaseName()}", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Set the tenant database connection
     */
    public function setTenantDatabaseConnection(Store $store): void
    {
        Config::set('database.connections.tenant.database', $store->getDatabaseName());
        DB::purge('tenant');
        DB::reconnect('tenant');
    }

    /**
     * Check if database exists
     */
    public function databaseExists(string $databaseName): bool
    {
        try {
            $result = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$databaseName]);
            return !empty($result);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Setup a complete tenant (database + migrations)
     */
    public function setupTenant(Store $store): bool
    {
        $databaseName = $store->getDatabaseName();
        
        // Create database if it doesn't exist
        if (!$this->databaseExists($databaseName)) {
            if (!$this->createDatabase($databaseName)) {
                return false;
            }
        }
        
        // Run migrations
        return $this->runMigrations($store);
    }
}
