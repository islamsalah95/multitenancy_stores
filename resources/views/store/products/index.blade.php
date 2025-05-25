@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Products</h2>
                <a href="{{ route('store.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add Product
                </a>
            </div>
        </div>
    </div>

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
                            <div>
                                <span class="h5 mb-0">${{ number_format($product->price, 2) }}</span>
                                <small class="text-muted d-block">Stock: {{ $product->stock }}</small>
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
                    <div class="card-footer">
                        <small class="text-muted">
                            Category: {{ $product->category->name }}
                        </small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection 