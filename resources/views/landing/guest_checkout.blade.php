@extends('landing.layouts.master')

@section('content')
<div class="container mt-5">
    <h3>Guest Checkout</h3>
    <form action="{{ route('checkout') }}" method="GET" id="guest-checkout-form">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" required placeholder="Enter your full name">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email">
        </div>
        <button type="submit" class="btn btn-primary">Proceed to Payment</button>
    </form>
</div>
@endsection
