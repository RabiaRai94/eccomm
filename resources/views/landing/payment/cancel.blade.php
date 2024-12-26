@extends('landing.layouts.master')

@section('content')
<div class="container m-5">
    <h2>Payment Cancelled</h2>
    <p>Your payment was not completed.</p>
    <a href="{{ route('checkout') }}" class="btn btn-warning">Try Again</a>
</div>
@endsection