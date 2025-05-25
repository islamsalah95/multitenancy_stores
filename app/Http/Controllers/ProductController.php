<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $store = Auth::guard('store')->user();

        // Set tenant context
        $store->makeCurrent();

        $products = Product::with('category')->latest()->paginate(10);
        return view('store.products.index', compact('products'));
    }

    public function create()
    {
        $store = Auth::guard('store')->user();

        // Set tenant context
        $store->makeCurrent();

        $categories = Category::where('is_active', true)->get();
        return view('store.products.create', compact('categories'));
    }

    public function show(Product $product)
    {
        $store = Auth::guard('store')->user();

        // Set tenant context
        $store->makeCurrent();

        // Check if product belongs to the current store
        if ($product->store_id !== $store->id) {
            abort(403, 'Unauthorized');
        }

        return view('store.products.show', compact('product'));
    }

    public function store(Request $request)
    {
        $store = Auth::guard('store')->user();

        // Set tenant context
        $store->makeCurrent();

        $validated = $request->validate([
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('product-images', 'public');
            $validated['image'] = $imagePath;
        }

        // Add store_id and create in tenant database
        $validated['store_id'] = $store->id;
        Product::create($validated);

        return redirect()->route('store.products.index')
            ->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $store = Auth::guard('store')->user();

        // Set tenant context
        $store->makeCurrent();

        // Check if product belongs to the current store
        if ($product->store_id !== $store->id) {
            abort(403, 'Unauthorized');
        }

        $categories = Category::where('is_active', true)->get();
        return view('store.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $store = Auth::guard('store')->user();

        // Set tenant context
        $store->makeCurrent();

        // Check if product belongs to the current store
        if ($product->store_id !== $store->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('product-images', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        return redirect()->route('store.products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $store = Auth::guard('store')->user();

        // Set tenant context
        $store->makeCurrent();

        // Check if product belongs to the current store
        if ($product->store_id !== $store->id) {
            abort(403, 'Unauthorized');
        }

        $product->delete();

        return redirect()->route('store.products.index')
            ->with('success', 'Product deleted successfully');
    }
}