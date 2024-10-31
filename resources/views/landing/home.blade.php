@extends('layouts.master')

@section('title', 'Home')
@section('description', 'Description')

@section('content')

<div class="container-fluid">
  @include('layouts.cart')
  <x-slider />

</div>
@endsection