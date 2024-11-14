@extends('admin.dashboard.layout.master')

@section('content')
<div class="container mt-5">
    <h1 class="text-center">Product Details</h1>

    <div class="card mt-4">
        <div class="card-body">
            <h2>{{ $product->name }}</h2>
            <p><strong>Description:</strong> {{ $product->description ?? 'No description available' }}</p>

            <p><strong>Category:</strong>
                {{ $product->category ? $product->category->name : 'Uncategorized' }}
            </p>

            <p><strong>Created At:</strong> {{ $product->created_at->format('d M Y, h:i A') }}</p>
            <p><strong>Updated At:</strong> {{ $product->updated_at->format('d M Y, h:i A') }}</p>

            <div class="mt-3">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">Edit Product</a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-success mt-4" data-bs-toggle="modal" data-bs-target="#addVariantModal">
        Add Variant
    </button>
    @include('admin.products.productvarientmodal')
</div>
@endsection