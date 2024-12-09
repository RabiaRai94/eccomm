@extends('landing.layouts.master')

@section('content')
<div class="container m-5">
    <h2>Payment Successful!</h2>
    <p>Thank you for your purchase.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Return Home</a>
</div>
@endsection
