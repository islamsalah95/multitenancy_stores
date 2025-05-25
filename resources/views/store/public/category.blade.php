@extends('layouts.public')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('store.public.home', $store) }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="row mb-5">
        <div class="col-md-12 text-center">
            @if($category->image)
                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="category-image mb-3" style="max-height: 200px; border-radius: 15px;">
            @endif
            <h1 class="display-5 mb-3">{{ $category->name }}</h1>
            @if($category->description)
                <p class="lead text-muted">{{ $category->description }}</p>
            @endif
        </div>
    </div>

    <!-- Products -->
    <div class="row">
        <div class="col-md-12">
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
                                        <a href="{{ route('store.public.product', [$store, $product]) }}" class="btn btn-sm btn-outline-primary">View</a>
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
                    <h5 class="text-muted">No products in this category</h5>
                    <p class="text-muted">Check back later for new products!</p>
                    <a href="{{ route('store.public.home', $store) }}" class="btn btn-primary">Browse All Products</a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.product-card:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.category-image {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>
@endsection
