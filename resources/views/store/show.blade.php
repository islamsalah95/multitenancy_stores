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
                                class="rounded-circle me-4" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-4" 
                                style="width: 120px; height: 120px;">
                                <i class="fas fa-store fa-4x text-muted"></i>
                            </div>
                        @endif
                        <div>
                            <h1 class="mb-2">{{ $store->name }}</h1>
                            <p class="text-muted mb-2">{{ $store->description }}</p>
                            @if($store->address)
                                <p class="mb-1">
                                    <i class="fas fa-map-marker-alt me-2"></i>{{ $store->address }}
                                </p>
                            @endif
                            @if($store->phone)
                                <p class="mb-0">
                                    <i class="fas fa-phone me-2"></i>{{ $store->phone }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Categories</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($categories as $category)
                        <a href="{{ route('store.category', ['store' => $store, 'category' => $category]) }}" 
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            {{ $category->name }}
                            <span class="badge bg-primary rounded-pill">{{ $category->products_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-4 mb-4">
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
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 