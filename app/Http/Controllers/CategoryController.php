<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $store = Auth::guard('store')->user();
        $categories = $store->categories()->withCount('products')->get();
        return view('store.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('store.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $store = Auth::guard('store')->user();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('category-images', 'public');
            $validated['image'] = $imagePath;
        }

        $store->categories()->create($validated);

        return redirect()->route('store.categories.index')
            ->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {
        $store = Auth::guard('store')->user();

        // Check if category belongs to the current store
        if ($category->store_id !== $store->id) {
            abort(403, 'Unauthorized');
        }

        return view('store.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $store = Auth::guard('store')->user();

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