<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test store
        Store::create([
            'name' => 'Test Store',
            'email' => 'store@example.com',
            'password' => Hash::make('password'),
            'domain' => '127.0.0.1',
            'database' => 'tenant_test_store',
            'is_active' => true,
        ]);
    }
}
