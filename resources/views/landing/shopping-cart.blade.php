@extends('landing.layouts.master')
@section('content')
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<div class="wrap-header-cart js-panel-cart">
    <div class="s-full js-hide-cart"></div>

    <div class="header-cart flex-col-l p-l-65 p-r-25">
        <div class="header-cart-title flex-w flex-sb-m p-b-8">
            <span class="mtext-103 cl2">
                Your Cart
            </span>
            <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                <i class="zmdi zmdi-close"></i>
            </div>
        </div>

        <div class="header-cart-content flex-w js-pscroll">
            @if($cartItems->isEmpty())
                <p class="stext-101 cl6 text-center">Your cart is empty!</p>
            @else
                <ul class="header-cart-wrapitem w-full">
                    @foreach($cartItems as $item)
                    <li class="header-cart-item flex-w flex-t m-b-12">
                        <div class="header-cart-item-img">
                            <img src="{{ $item->options->image }}" alt="IMG">
                        </div>
                        <div class="header-cart-item-txt p-t-8">
                            <a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
                                {{ $item->name }}
                            </a>
                            <span class="header-cart-item-info">
                                {{ $item->qty }} x ${{ number_format($item->price, 2) }}
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <div class="w-full">
                    <div class="header-cart-total w-full p-tb-40">
                        Total: ${{ number_format($cartTotal, 2) }}
                    </div>
                    <div class="header-cart-buttons flex-w w-full">
                        <a href="{{ route('cart.index') }}" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
                            View Cart
                        </a>
                        <a href="{{ route('checkout.index') }}" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
                            Check Out
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Cart Table Section -->
<form action="{{ route('cart.update') }}" method="POST" class="bg0 p-t-75 p-b-85">
    @csrf
    @method('PUT')
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
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
                                    <img src="{{ $item->options->image }}" alt="IMG">
                                </div>
                            </td>
                            <td class="column-2">{{ $item->name }}</td>
                            <td class="column-3">${{ number_format($item->price, 2) }}</td>
                            <td class="column-4">
                                <input class="mtext-104 cl3 txt-center num-product" type="number" name="items[{{ $item->rowId }}]" value="{{ $item->qty }}">
                            </td>
                            <td class="column-5">${{ number_format($item->price * $item->qty, 2) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <button type="submit" class="flex-c-m stext-101 cl2 size-119 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-10">
                    Update Cart
                </button>
            </div>
            <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
                <div class="bor10 p-lr-40 p-t-30 p-b-40">
                    <h4 class="mtext-109 cl2 p-b-30">Cart Totals</h4>
                    <div class="flex-w flex-t p-t-27 p-b-33">
                        <div class="size-208">
                            <span class="mtext-101 cl2">Total:</span>
                        </div>
                        <div class="size-209 p-t-1">
                            <span class="mtext-110 cl2">${{ number_format($cartTotal, 2) }}</span>
                        </div>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
