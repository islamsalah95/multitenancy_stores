@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('store.show', $store) }}">{{ $store->name }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-3">{{ $category->name }}</h1>
            @if($category->description)
                <p class="text-muted">{{ $category->description }}</p>
            @endif
        </div>
        <div class="col-md-4">
            @if($category->image)
                <img src="{{ asset('storage/' . $category->image) }}" class="img-fluid rounded" 
                    alt="{{ $category->name }}">
            @endif
        </div>
    </div>

    <div class="row">
        @forelse($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" 
                            alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                            style="height: 200px;">
                            <i class="fas fa-box fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($product->description, 100) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">${{ number_format($product->price, 2) }}</span>
                            <a href="{{ route('store.product', ['store' => $store, 'product' => $product]) }}" 
                                class="btn btn-primary">
                                View Details
                            </a>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <small class="text-muted">
                            @if($product->stock > 0)
                                <span class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>In Stock
                                </span>
                            @else
                                <span class="text-danger">
                                    <i class="fas fa-times-circle me-1"></i>Out of Stock
                                </span>
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No products found in this category.
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection 