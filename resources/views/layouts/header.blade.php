<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description"
        content="Chameleon Admin is a modern Bootstrap 4 webapp &amp; admin dashboard html template with a large number of components, elegant design, clean and organized code.">
    <meta name="keywords"
        content="admin template, Chameleon admin template, dashboard template, gradient admin template, responsive admin template, webapp, eCommerce dashboard, analytic dashboard">
    <meta name="author" content="ThemeSelect">
    <title>@yield('title')</title>
    <link rel="apple-touch-icon" href="{{ asset('assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/ico/abc-favicon.ico') }}">
    {{-- <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/openSans.css') }}">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/forms/toggle/switchery.css') }}"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/plugins/forms/switch.css') }}"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/core/colors/palette-switch.css') }}"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/charts/chartist.css') }}"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/charts/chartist-plugin-tooltip.css') }}"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/timeline/vertical-timeline.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/pickers/daterange/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/plugins/pickers/daterange/daterange.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/forms/selects/select2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/tables/datatable/datatables.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/ui/prism.min.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/extensions/toastr.css') }}">
    @stack('vendors-css')
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/plugins/extensions/toastr.css') }}">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/core/menu/menu-types/vertical-menu.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/core/colors/palette-gradient.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/feather/style.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/simple-line-icons/style.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/core/colors/palette-gradient.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/plugins/forms/checkboxes-radios.min.css') }}"> --}}
     <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cropper.css') }}">
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/pages/users.min.css')}}"> --}}
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/pages/timeline.min.css')}}"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/dashboard-ecommerce.min.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/plugins/animate/animate.min.css') }}">
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/pages/advanced-cards.min.css')}}"> --}}

    <!-- END: Page CSS-->
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/laravel-datatable.css')}}"> --}}
    <!-- DATATABLE: ended-->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/css/uikit.min.css" /> --}}
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('formvalidation/dist/css/formValidation.min.css') }}" />
    {{-- <link rel="stylesheet" href="https://unpkg.com/tachyons@4.10.0/css/tachyons.min.css"> --}}

    <!-- BEGIN: Custom CSS-->
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.css') }}"> --}}
     <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.timepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/imageUploader/image-uploader.css') }}" />

    {{-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"> --}}
    <link rel="stylesheet" href="{{ asset('assets/datetimepicker/build/jquery.datetimepicker.min.css') }}" />
    
    @stack('page-css')
    <!-- END: Custom CSS-->

</head>
<div id="Full_Loader" class="d-flex justify-content-center align-items-center">
    <div class="loadingio-spinner-ripple-stmatfv2p2s">
        <div class="ldio-50d1oqhko">
            <div></div>
            <div></div>
        </div>
    </div>
</div>
