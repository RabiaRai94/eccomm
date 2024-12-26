@extends('landing.layouts.master')

@section('content')

<div class="container mt-5">
    @if($cartItems->isEmpty())
        <div class="text-center">
            <h3>Your cart is empty</h3>
            <a href="{{ route('shopproducts') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    @else
        <h3>Your Cart</h3>
        <form action="{{ route('cart.update') }}" method="POST">
            @csrf
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr>
                            <td><img src="{{ asset('storage/' . $item->product->image) }}" alt="Product" style="width: 50px;"></td>
                            <td>{{ $item->product->name }}</td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>
                                <input type="number" name="quantity[{{ $item->id }}]" value="{{ $item->quantity }}" min="1">
                            </td>
                            <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                            <td>
                                <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                <button type="submit" class="btn btn-info">Update Cart</button>
                <a href="{{ route('checkout') }}" class="btn btn-success">Checkout</a>
            </div>
        </form>
    @endif
</div>

@endsection
