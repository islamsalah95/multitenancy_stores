@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 mb-4">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded" 
                    alt="{{ $product->name }}">
            @else
                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                    style="height: 400px;">
                    <i class="fas fa-box fa-5x text-muted"></i>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('store.show', $store) }}">{{ $store->name }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('store.category', ['store' => $store, 'category' => $product->category]) }}">
                            {{ $product->category->name }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>

            <h1 class="mb-3">{{ $product->name }}</h1>
            <h3 class="text-primary mb-4">${{ number_format($product->price, 2) }}</h3>

            <div class="mb-4">
                <h5>Description</h5>
                <p class="text-muted">{{ $product->description }}</p>
            </div>

            <div class="mb-4">
                <h5>Stock Status</h5>
                @if($product->stock > 0)
                    <span class="badge bg-success">In Stock ({{ $product->stock }} available)</span>
                @else
                    <span class="badge bg-danger">Out of Stock</span>
                @endif
            </div>

            <div class="mb-4">
                <h5>Store Information</h5>
                <div class="d-flex align-items-center mb-2">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" 
                            class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" 
                            style="width: 40px; height: 40px;">
                            <i class="fas fa-store text-muted"></i>
                        </div>
                    @endif
                    <div>
                        <h6 class="mb-0">{{ $store->name }}</h6>
                        @if($store->address)
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $store->address }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($relatedProducts->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">Related Products</h3>
                <div class="row">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col-md-3 mb-4">
                            <div class="card h-100">
                                @if($relatedProduct->image)
                                    <img src="{{ asset('storage/' . $relatedProduct->image) }}" class="card-img-top" 
                                        alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                        style="height: 200px;">
                                        <i class="fas fa-box fa-3x text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                                    <p class="card-text text-muted">
                                        {{ Str::limit($relatedProduct->description, 100) }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 mb-0">${{ number_format($relatedProduct->price, 2) }}</span>
                                        <a href="{{ route('store.product', ['store' => $store, 'product' => $relatedProduct]) }}" 
                                            class="btn btn-primary">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 