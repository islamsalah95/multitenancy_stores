@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Categories</h2>
                <a href="{{ route('store.categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add Category
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" class="card-img-top" 
                            alt="{{ $category->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                            style="height: 200px;">
                            <i class="fas fa-folder fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($category->description, 100) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">
                                <i class="fas fa-box me-1"></i>{{ $category->products_count }} Products
                            </span>
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
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection 