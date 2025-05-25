@extends('layouts.public')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('store.public.home', $store) }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('store.public.category', [$store, $product->category]) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Details -->
    <div class="row mb-5">
        <div class="col-md-6">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid product-image" style="border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px; border-radius: 15px;">
                    <i class="fas fa-box fa-5x text-muted"></i>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            <div class="product-details">
                <h1 class="display-6 mb-3">{{ $product->name }}</h1>
                <div class="mb-3">
                    <span class="badge bg-secondary">{{ $product->category->name }}</span>
                </div>
                <h3 class="text-primary mb-4">${{ number_format($product->price, 2) }}</h3>
                
                @if($product->stock > 0)
                    <div class="mb-3">
                        <span class="badge bg-success">
                            <i class="fas fa-check me-1"></i>In Stock ({{ $product->stock }} available)
                        </span>
                    </div>
                @else
                    <div class="mb-3">
                        <span class="badge bg-danger">
                            <i class="fas fa-times me-1"></i>Out of Stock
                        </span>
                    </div>
                @endif

                <div class="mb-4">
                    <h5>Description</h5>
                    <p class="text-muted">{{ $product->description }}</p>
                </div>

                @if($store->phone)
                    <div class="d-grid gap-2">
                        <a href="tel:{{ $store->phone }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-phone me-2"></i>Call to Order: {{ $store->phone }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-4">Related Products</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 product-card">
                            @if($relatedProduct->image)
                                <img src="{{ asset('storage/' . $relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-box fa-2x text-muted"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                                <p class="card-text text-muted small">
                                    {{ Str::limit($relatedProduct->description, 80) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h6 text-primary mb-0">${{ number_format($relatedProduct->price, 2) }}</span>
                                    <a href="{{ route('store.public.product', [$store, $relatedProduct]) }}" class="btn btn-sm btn-outline-primary">View</a>
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

<style>
.product-card:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.product-image {
    max-width: 100%;
    height: auto;
}

.product-details {
    padding: 20px 0;
}

@media (max-width: 768px) {
    .product-details {
        padding: 20px 0 0 0;
    }
}
</style>
@endsection
