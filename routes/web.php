<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

// Check if this is a tenant domain
$host = request()->getHost();
$port = request()->getPort();

// Define landlord domains (main application)
$landlordDomains = [
    'localhost',
    '127.0.0.1',
    'localhost:8000',
    '127.0.0.1:8000'
];

// Check if current host is a landlord domain
$isLandlordDomain = in_array($host, $landlordDomains) ||
                   in_array($host . ':' . $port, $landlordDomains);

if ($isLandlordDomain) {
    // LANDLORD ROUTES - Main application routes (store management)

    // Public routes (landlord)
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Store authentication routes (landlord)
    Route::middleware(['guest:store'])->group(function () {
        Route::get('/register', [StoreController::class, 'showRegistrationForm'])->name('store.register');
        Route::post('/register', [StoreController::class, 'register']);
        Route::get('/login', [StoreController::class, 'showLoginForm'])->name('store.login');
        Route::post('/login', [StoreController::class, 'login']);
    });

    Route::post('/store/logout', [StoreController::class, 'logout'])->name('store.logout');

    // Protected store management routes (landlord)
    Route::middleware(['auth:store'])->name('store.')->group(function () {
        Route::get('/dashboard', [StoreController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [StoreController::class, 'profile'])->name('profile');
        Route::put('/profile', [StoreController::class, 'updateProfile'])->name('profile.update');

        // Categories
        Route::resource('categories', CategoryController::class);

        // Products
        Route::resource('products', ProductController::class);
    });

    // Debug route for landlord
    Route::get('/debug', function () {
        return response()->json([
            'host' => request()->getHost(),
            'url' => request()->fullUrl(),
            'type' => 'landlord',
            'stores' => \App\Models\Store::all(['id', 'name', 'domain'])->toArray()
        ]);
    });

} else {
    // TENANT ROUTES - For tenant domains (public store only)
    Route::middleware(['tenant'])->group(function () {
        Route::get('/', [HomeController::class, 'tenantHome'])->name('tenant.home');
        Route::get('/category/{category}', [HomeController::class, 'tenantCategory'])->name('tenant.category');
        Route::get('/product/{product}', [HomeController::class, 'tenantProduct'])->name('tenant.product');

        // Debug route for tenant
        Route::get('/debug', function () {
            $tenant = app(\Spatie\Multitenancy\Contracts\IsTenant::class);
            return response()->json([
                'host' => request()->getHost(),
                'url' => request()->fullUrl(),
                'type' => 'tenant',
                'tenant_found' => $tenant !== null,
                'database' => $tenant ? $tenant->getDatabaseName() : null
            ]);
        });

        // Redirect any other routes to tenant home
        Route::fallback(function () {
            return redirect()->route('tenant.home');
        });
    });
}
