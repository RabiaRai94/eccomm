@extends('landing.layouts.master')

@section('content')
<div class="wrap-header-cart js-panel-cart">
    <div class="s-full js-hide-cart"></div>

    <div class="header-cart flex-col-l p-l-65 p-r-25">
        <div class="header-cart-title flex-w flex-sb-m p-b-8">
            <span class="mtext-103 cl2">Your Cart</span>
            <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                <i class="zmdi zmdi-close"></i>
            </div>
        </div>

        <div class="header-cart-content flex-w js-pscroll">
            @if($cartItems->isEmpty())
            <p class="text-center w-100 p-4">Your cart is currently empty.</p>
            @else
            <ul class="header-cart-wrapitem w-full">
                @foreach($cartItems as $item)
                <li class="header-cart-item flex-w flex-t m-b-12">
                    <div class="header-cart-item-img">
                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                    <div class="header-cart-item-txt p-t-8">
                        <a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
                            {{ $item->product->name }}
                        </a>
                        <span class="header-cart-item-info">
                            {{ $item->quantity }} x ${{ number_format($item->price, 2) }}
                        </span>
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="header-cart-total w-full p-tb-40">
                Total: ${{ number_format($total, 2) }}
            </div>
            @endif

        </div>
    </div>
</div>

<div class="container">
    <div class="bread-crumb bg-light p-3 rounded mb-3 mt-5">
        <a href="/" class="text-decoration-none text-dark">
            Home
            <i class="fa fa-angle-right mx-2"></i>
        </a>
        <span class="text-muted">Shopping Cart</span>
    </div>

    <div class="bg-white p-4 shadow-sm rounded">
        @if($cartItems->isEmpty())
        <div class="text-center py-5">
            <h3>Your Cart is Empty</h3>
            <p><a href="/" class="btn btn-outline-primary">Continue Shopping</a></p>
        </div>
        @else
        <form method="POST" action="">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                            </td>
                            <td>{{ $item->product->name }}</td>
                            <td>${{ number_format($item->product->price, 2) }}</td>
                            <td>
                                <input type="number" id="quantity-{{ $item->id }}" name="quantity[{{ $item->id }}]" class="form-control" value="{{ $item->quantity }}" min="1" onchange="updateQuantity('{{ $item->id }}', 0)">
                            </td>
                            <td id="total-{{ $item->id }}" data-price="{{ $item->product->price }}">${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                            <td>
                                <button type="button" class="btn btn-danger" onclick="removeFromCart('{{ route('cart.remove', $item->id) }}')">Remove</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-info">Update Cart</button>
            </div>
        </form>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Cart Totals</h5>
                <p class="card-text"><strong>Subtotal: </strong><span id="subtotal">${{ number_format($total, 2) }}</span></p>
                <a href="" class="btn btn-success ">Proceed to Checkout</a>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    function updateQuantity(itemId, change) {
        let quantityInput = document.getElementById('quantity-' + itemId);
        let newQuantity = parseInt(quantityInput.value) + change;
        if (newQuantity < 1) return;
        quantityInput.value = newQuantity;
        let pricePerUnit = parseFloat(document.getElementById('total-' + itemId).getAttribute('data-price'));
        document.getElementById('total-' + itemId).innerText = (newQuantity * pricePerUnit).toFixed(2);
        updateSubtotal();
    }

    function updateSubtotal() {
        let subtotal = 0;
        document.querySelectorAll('[id^="total-"]').forEach(totalElement => {
            subtotal += parseFloat(totalElement.innerText);
        });
        document.getElementById('subtotal').innerText = subtotal.toFixed(2);
    }


    function removeFromCart(url) {
        if (confirm('Are you sure you want to remove this item?')) {
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            }).then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Failed to remove item.');
                }
            }).catch(error => console.error('Error:', error));
        }
    }
</script>
@endsection