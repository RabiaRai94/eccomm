@extends('landing.layouts.master')

@section('content')
<div class="container p-5">
    <h2>Checkout</h2>
    <p>Total: ${{ number_format($total, 2) }}</p>
    <form action="{{ route('payment.process') }}" method="POST">
        @csrf
        <input type="hidden" name="total" value="{{ $total }}">
        <button type="submit" class="btn btn-success">Pay with Stripe</button>
    </form>
</div>
@endsection
