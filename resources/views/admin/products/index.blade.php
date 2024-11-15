@extends('admin.dashboard.layout.master')

@section('content')
<div class="container">
    <h2>Products</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add Product</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Variants</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->category->name ?? 'N/A' }}</td>
                <td>
                    @foreach ($product->variants as $variant)
                    <div class="mb-2">
                        <p>
                            <strong>Size:</strong> {{ $variant->size }},
                            <strong>Price:</strong> ${{ $variant->price }},
                            <strong>Stock:</strong> {{ $variant->stock }}
                        </p>
                        
                        <div class="variant-images">
                            @foreach ($variant->attachments as $attachment)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $attachment->file_path) }}" alt="{{ $attachment->file_name }}" style="width: 100px; height: auto;">
                                <p>{{ $attachment->file_name }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    <button type="button" class="btn btn-success mt-4 add-variant-btn" data-bs-toggle="modal" 
                            data-bs-target="#addVariantModal" data-product-id="{{ $product->id }}">
                        Add Variant
                    </button>
                </td>
                <td>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-secondary">Edit</a>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-secondary">Show</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('admin.products.productvarientmodal')

@endsection
