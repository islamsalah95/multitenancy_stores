@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12 text-center">
            <h1 class="display-4 mb-3">Welcome to Store Platform</h1>
            <p class="lead text-muted">Discover amazing stores and their products</p>
        </div>
    </div>

    <div class="row">
        @foreach($stores as $store)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" class="card-img-top" alt="{{ $store->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-store fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $store->name }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($store->description, 100) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">
                                <i class="fas fa-box me-1"></i>{{ $store->products_count }} Products
                            </span>
                            <a href="http://{{ $store->domain }}:8000" target="_blank" class="btn btn-outline-primary">
                                Visit Store
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $stores->links() }}
    </div>
</div>
@endsection