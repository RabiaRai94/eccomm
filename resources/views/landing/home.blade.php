@extends('landing.layouts.master')

@section('title', 'Home')
@section('description', 'Description')

@section('content')

<div class="container-fluid">
  @include('landing.layouts.cart')
  @include('landing.layouts.slider')
</div>
@endsection