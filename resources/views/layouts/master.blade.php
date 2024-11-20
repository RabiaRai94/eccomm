<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.header')

<body class="vertical-layout vertical-menu 2-columns   fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-primary" data-col="2-columns">
    <!-- Toastr -->
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            @php
                RealRashid\SweetAlert\Facades\Alert::error($error);
                break;
            @endphp
        @endforeach
    @endif

    <!-- End Toastr -->


    @include('layouts.top')
    @include('layouts.sidebar')

    @yield('content')

    @include('layouts.footer')
    @include('layouts.scripts')
   


</body>

</html>
