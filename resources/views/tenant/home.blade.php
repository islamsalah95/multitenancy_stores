@extends('layouts.tenant')

@section('content')
<div class="container">
    <!-- Store Header -->
    <div class="row mb-5">
        <div class="col-md-12 text-center">
            @if($store->logo)
                <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="store-logo mb-3" style="max-height: 150px;">
            @endif
            <h1 class="display-4 mb-3">{{ $store->name }}</h1>
            @if($store->description)
                <p class="lead text-muted">{{ $store->description }}</p>
            @endif
            @if($store->address)
                <p class="text-muted">
                    <i class="fas fa-map-marker-alt me-1"></i>{{ $store->address }}
                </p>
            @endif
        </div>
    </div>

    <!-- Categories -->
    @if($categories->count() > 0)
    <div class="row mb-5">
        <div class="col-md-12">
            <h3 class="mb-4">Categories</h3>
            <div class="row">
                @foreach($categories as $category)
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('tenant.category', $category) }}" class="text-decoration-none">
                            <div class="card h-100 category-card">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" class="card-img-top" alt="{{ $category->name }}" style="height: 150px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                        <i class="fas fa-folder fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body text-center">
                                    <h6 class="card-title">{{ $category->name }}</h6>
                                    <small class="text-muted">{{ $category->products_count }} products</small>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Products -->
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-4">Products</h3>
            @if($products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-md-3 mb-4">
                            <div class="card h-100 product-card">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-box fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title">{{ $product->name }}</h6>
                                    <p class="card-text text-muted small">
                                        {{ Str::limit($product->description, 80) }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h6 text-primary mb-0">${{ number_format($product->price, 2) }}</span>
                                        <a href="{{ route('tenant.product', $product) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No products available</h5>
                    <p class="text-muted">Check back later for new products!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.category-card:hover, .product-card:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.store-logo {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
</style>
@endsection
