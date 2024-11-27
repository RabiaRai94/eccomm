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
            <ul class="header-cart-wrapitem w-full">
                @foreach($cartItems as $item)
                <li class="header-cart-item flex-w flex-t m-b-12">
                    <div class="header-cart-item-img">
                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                    </div>
                    <div class="header-cart-item-txt p-t-8">
                        <a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
                            {{ $item->product->name }}
                        </a>
                        <span class="header-cart-item-info">
                            {{ $item->quantity }} x ${{ number_format($item->product->price, 2) }}
                        </span>
                    </div>
                </li>
                @endforeach
            </ul>

            <div class="w-full">
                <div class="header-cart-total w-full p-tb-40">
                    Total: ${{ number_format($total, 2) }}
                </div>

                <div class="header-cart-buttons flex-w w-full">
                    <a href="" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
                        View Cart
                    </a>
                    <a href="" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
                        Check Out
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
        <a href="/" class="stext-109 cl8 hov-cl1 trans-04">
            Home
            <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
        </a>
        <span class="stext-109 cl4">Shopping Cart</span>
    </div>
</div>

<form class="bg0 p-t-75 p-b-85" method="POST" action="">
    @csrf
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                <div class="m-l-25 m-r--38 m-lr-0-xl">
                    <div class="wrap-table-shopping-cart">
                        <table class="table-shopping-cart">
                            <tr class="table_head">
                                <th class="column-1">Product</th>
                                <th class="column-2"></th>
                                <th class="column-3">Price</th>
                                <th class="column-4">Quantity</th>
                                <th class="column-5">Total</th>
                            </tr>
                            @foreach($cartItems as $item)
                            <tr class="table_row">
                                <td class="column-1">
                                    <div class="how-itemcart1">
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                    </div>
                                </td>
                                <td class="column-2">{{ $item->product->name }}</td>
                                <td class="column-3">${{ number_format($item->product->price, 2) }}</td>
                                <td class="column-4">{{ $item->quantity }}</td>
                                <td class="column-5">${{ number_format(($item->product->price ?? $item->price) * $item->quantity, 2) }}</td>
                                <td>
                                    <!-- Using the unique identifier as a fallback -->
                                    <button data-id="{{ $item->id }}">Remove</button>
                                    <form action="{{ route('cart.remove' }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach

                        </table>
                    </div>

                    <div class="flex-w flex-sb-m bor15 p-t-18 p-b-15 p-lr-40 p-lr-15-sm">
                        <button class="flex-c-m stext-101 cl2 size-119 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-10">
                            Update Cart
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
                <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                    <h4 class="mtext-109 cl2 p-b-30">Cart Totals</h4>
                    <div class="flex-w flex-t bor12 p-b-13">
                        <div class="size-208">
                            <span class="stext-110 cl2">Subtotal:</span>
                        </div>
                        <div class="size-209">
                            <span id="subtotal" class="mtext-110 cl2">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                    <button class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function updateQuantity(itemId, change) {
        let quantityInput = document.getElementById('quantity-' + itemId);
        let newQuantity = parseInt(quantityInput.value) + change;

        if (newQuantity < 1) return; // prevent negative values

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
    document.querySelectorAll('.btn-danger').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to remove this item?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection