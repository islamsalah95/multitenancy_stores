<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class HomeController extends Controller
{
    public function index()
    {
        $stores = Store::where('is_active', true)
            ->withCount('products')
            ->latest()
            ->paginate(12);

        return view('home', compact('stores'));
    }

    public function store(Store $store)
    {
        $categories = $store->categories()
            ->where('is_active', true)
            ->withCount('products')
            ->get();

        $products = $store->products()
            ->where('is_active', true)
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('store.show', compact('store', 'categories', 'products'));
    }

    public function category(Store $store, Category $category)
    {
        $products = $category->products()
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('store.category', compact('store', 'category', 'products'));
    }

    public function product(Store $store, Product $product)
    {
        $relatedProducts = $product->category->products()
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('store.product', compact('store', 'product', 'relatedProducts'));
    }

    /**
     * Tenant home page (accessed via domain)
     */
    public function tenantHome()
    {
        $store = app(\Spatie\Multitenancy\Contracts\IsTenant::class);

        if (!$store) {
            abort(404, 'Store not found');
        }

        // In tenant database, all data belongs to this store, so no need to filter by store_id
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get();

        $products = Product::where('is_active', true)
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('tenant.home', compact('store', 'categories', 'products'));
    }

    /**
     * Tenant category page (accessed via domain)
     */
    public function tenantCategory(Category $category)
    {
        $store = app(\Spatie\Multitenancy\Contracts\IsTenant::class);

        if (!$store) {
            abort(404, 'Store not found');
        }

        // In tenant database, all data belongs to this store
        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('tenant.category', compact('store', 'category', 'products'));
    }

    /**
     * Tenant product page (accessed via domain)
     */
    public function tenantProduct(Product $product)
    {
        $store = app(\Spatie\Multitenancy\Contracts\IsTenant::class);

        if (!$store) {
            abort(404, 'Store not found');
        }

        // In tenant database, all data belongs to this store
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('tenant.product', compact('store', 'product', 'relatedProducts'));
    }

    /**
     * Public store page (accessed via URL parameter)
     */
    public function publicStore(Store $store)
    {
        if (!$store->is_active) {
            abort(404, 'Store not found');
        }

        // Get categories and products from tenant database
        $categories = Category::where('store_id', $store->id)
            ->where('is_active', true)
            ->withCount('products')
            ->get();

        $products = Product::where('store_id', $store->id)
            ->where('is_active', true)
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('store.public.home', compact('store', 'categories', 'products'));
    }

    /**
     * Public category page (accessed via URL parameter)
     */
    public function publicCategory(Store $store, Category $category)
    {
        if (!$store->is_active || $category->store_id !== $store->id) {
            abort(404, 'Category not found');
        }

        $products = Product::where('category_id', $category->id)
            ->where('store_id', $store->id)
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('store.public.category', compact('store', 'category', 'products'));
    }

    /**
     * Public product page (accessed via URL parameter)
     */
    public function publicProduct(Store $store, Product $product)
    {
        if (!$store->is_active || $product->store_id !== $store->id) {
            abort(404, 'Product not found');
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('store_id', $store->id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('store.public.product', compact('store', 'product', 'relatedProducts'));
    }
}