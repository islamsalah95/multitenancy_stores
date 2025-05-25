<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $store = Auth::guard('store')->user();

        // Set tenant context
        $store->makeCurrent();

        $categories = Category::withCount('products')->get();
        return view('store.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('store.categories.create');
    }

    public function show(Category $category)
    {
        $store = Auth::guard('store')->user();

        // Set tenant context
        $store->makeCurrent();

        // Check if category belongs to the current store
        if ($category->store_id !== $store->id) {
            abort(403, 'Unauthorized');
        }

        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('store.categories.show', compact('category', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $store = Auth::guard('store')->user();

        // Set tenant context
        $store->makeCurrent();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('category-images', 'public');
            $validated['image'] = $imagePath;
        }

        // Add store_id and create in tenant database
        $validated['store_id'] = $store->id;
        Category::create($validated);

        return redirect()->route('store.categories.index')
            ->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {
        $store = Auth::guard('store')->user();

        // Set tenant context
        $store->makeCurrent();

        // Check if category belongs to the current store
        if ($category->store_id !== $store->id) {
            abort(403, 'Unauthorized');
        }

        return view('store.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $store = Auth::guard('store')->user();

        // Set tenant context
        $store->makeCurrent();

        // Check if category belongs to the current store
        if ($category->store_id !== $store->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('category-images', 'public');
            $validated['image'] = $imagePath;
        }

        $category->update($validated);

        return redirect()->route('store.categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $store = Auth::guard('store')->user();

        // Set tenant context
        $store->makeCurrent();

        // Check if category belongs to the current store
        if ($category->store_id !== $store->id) {
            abort(403, 'Unauthorized');
        }

        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with associated products');
        }

        $category->delete();

        return redirect()->route('store.categories.index')
            ->with('success', 'Category deleted successfully');
    }
}