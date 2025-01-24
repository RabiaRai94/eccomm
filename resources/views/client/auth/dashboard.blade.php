@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Customer Dashboard</h1>
    <p>Welcome, {{ Auth::guard('customer')->user()->name }}</p>
</div>
@endsection
