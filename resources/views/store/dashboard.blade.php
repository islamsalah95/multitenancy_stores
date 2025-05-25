@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        @if($store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" 
                                class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" 
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-store fa-2x text-muted"></i>
                            </div>
                        @endif
                        <div>
                            <h2 class="mb-1">{{ $store->name }}</h2>
                            <p class="text-muted mb-0">{{ $store->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Categories</h5>
                    <h2 class="mb-0">{{ $categories->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h2 class="mb-0">{{ $products->sum('products_count') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Active Products</h5>
                    <h2 class="mb-0">{{ $products->where('is_active', true)->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Categories</h5>
                    <a href="{{ route('store.categories.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add Category
                    </a>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($categories as $category)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $category->name }}</h6>
                                    <small class="text-muted">{{ $category->products_count }} products</small>
                                </div>
                                <div class="btn-group">
                                    <a href="{{ route('store.categories.edit', $category) }}" 
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('store.categories.destroy', $category) }}" 
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this category?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Products</h5>
                    <a href="{{ route('store.products.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add Product
                    </a>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($products as $product)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $product->name }}</h6>
                                        <small class="text-muted">{{ $product->category->name }}</small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('store.products.edit', $product) }}" 
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('store.products.destroy', $product) }}" 
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 