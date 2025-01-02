@extends('landing.layouts.master')

@section('content')
<div class="container m-5">
    <h2>Payment Successful!</h2>
    <p>{{ $message }}</p>
    <p>Thank you for your purchase.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Return Home</a><br>
    <!-- <p><a href="{{ route('shopproducts') }}" class="btn btn-outline-primary">Continue to shop more</a></p> -->
</div>
@endsection
<script src="https://js.stripe.com/v3/"></script>