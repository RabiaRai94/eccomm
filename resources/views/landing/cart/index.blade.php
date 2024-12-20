@extends('landing.layouts.master')

@section('content')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
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
                        <img src="{{ asset('storage/' . ($item->product->image ?? 'default-image.jpg')) }}" alt="{{ $item->product->name }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">

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
            <p><a href="{{ route('shopproducts') }}" class="btn btn-outline-primary">Continue Shopping</a></p>
        </div>
        @else
        <div id="cart-container">

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
                                    <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('images/default-image.jpg') }}" alt="{{ $item->product->name }}">

                                </td>
                                <td>{{ $item->product->name }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>

                                <td>
                                    <input
                                        type="number"
                                        id="quantity-{{ $item->id }}"
                                        name="quantity[{{ $item->id }}]"
                                        class="form-control"
                                        value="{{ $item->quantity }}"
                                        data-item-id="{{ $item->id }}"
                                        data-max-stock="{{ $item->max_stock }}"
                                        data-price="{{ $item->price }}"
                                        min="1">

                                </td>
                                <td id="total-{{ $item->id }}" data-price="{{ $item->price }}">${{ number_format($item->price * $item->quantity, 2) }}</td>

                                <td>
                                    <button type="button" class="btn btn-danger" onclick="removeFromCart('{{ route('cart.remove', $item->id) }}')">Remove</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end">
                    <form method="POST" action="{{ route('cart.update') }}">
                        @csrf
                        <button type="submit" class="btn btn-info">Update Cart</button>
                    </form>

                </div>
            </form>
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Cart Totals</h5>
                    <p class="card-text"><strong>Subtotal: </strong><span id="subtotal">${{ number_format($total, 2) }}</span></p>
                    <form action="{{ route('payment.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="total" value="{{ $total }}">
                        <button type="submit" class="btn btn-success">Proceed to Checkout</button>
                    </form>

                </div>
            </div>

        </div>

        @endif
    </div>
</div>

<script>
    document.querySelectorAll('[id^="quantity-"]').forEach(input => {
        input.addEventListener('input', function() {
            const maxStock = parseInt(this.getAttribute('data-max-stock'), 10);
            let quantity = parseInt(this.value, 10);

            // Validate the input value to not exceed max stock and not go below 1
            if (isNaN(quantity) || quantity < 1) {
                quantity = 1; // Set to minimum allowed
            } else if (quantity > maxStock) {
                quantity = maxStock; // Set to maximum allowed stock
            }

            // Update the input value dynamically
            this.value = quantity;

            // Update the total price for this item
            const itemId = this.getAttribute('data-item-id');
            const pricePerUnit = parseFloat(this.getAttribute('data-price'));
            const totalElement = document.getElementById('total-' + itemId);
            totalElement.textContent = '$' + (quantity * pricePerUnit).toFixed(2);

            // Update the cart subtotal
            updateCartSubtotal();
        });
    });

    function updateCartSubtotal() {
        let subtotal = 0;
        document.querySelectorAll('[id^="total-"]').forEach(totalElement => {
            subtotal += parseFloat(totalElement.textContent.replace('$', ''));
        });
        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
    }


    function addToCart(variantId, quantity) {
        $.ajax({
            url: '/cart/add',
            method: 'POST',
            data: {
                variant_id: variantId,
                quantity: quantity,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(response) {
                alert(response.message);
               
                loadCartDetails();
            }
        });
    }

    function loadCartDetails() {
        $.get('/cart', function(data) {
            $('#cart-container').html(data);
        });
    }

    // function updateQuantity(itemId, change, maxStock) {
    //     let quantityInput = document.getElementById('quantity-' + itemId);
    //     let currentQuantity = parseInt(quantityInput.value);
    //     let newQuantity = currentQuantity + change;

    //     if (newQuantity > maxStock) {
    //         alert(`Only ${maxStock} items are available in stock.`);
    //         return;
    //     }

    //     if (newQuantity < 1) {
    //         alert("Quantity cannot be less than 1.");
    //         return;
    //     }


    //     quantityInput.value = newQuantity;


    //     let pricePerUnit = parseFloat(document.getElementById('total-' + itemId).getAttribute('data-price'));
    //     document.getElementById('total-' + itemId).innerText = '$' + (newQuantity * pricePerUnit).toFixed(2);


    //     updateSubtotal();
    // }



    // function updateSubtotal() {
    //     let subtotal = 0;
    //     document.querySelectorAll('[id^="total-"]').forEach(totalElement => {
    //         subtotal += parseFloat(totalElement.innerText.replace('$', ''));
    //     });
    //     document.getElementById('subtotal').innerText = '$' + subtotal.toFixed(2);
    // }


    function removeFromCart(url) {
        if (confirm('Are you sure you want to remove this item?')) {
            fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        const cartCount = response.cartCount;
                        $('.icon-header-noti.js-show-cart').attr('data-notify', cartCount);
                        loadCartDetails();
                    } else {
                        alert('Failed to remove item.');
                    }
                })
            // .catch(error => {
            //     console.error('Error:', error);
            //     alert('An error occurred while removing the item.');
            // });
        }
    }

    function loadCartDetails() {
        $.ajax({
            url: '/cart',
            type: 'GET',
            success: function(data) {
                $('#cart-container').html(data);
            }
        });
    }

    setInterval(function() {
        $.ajax({
            url: '/cart/count',
            method: 'GET',
            success: function(response) {
                $('.icon-header-noti.js-show-cart').attr('data-notify', response.cartCount);
            }
        });
    }, 60000); // Refresh every 60 seconds
</script>
@endsection