<!-- BEGIN: Vendor JS-->
<script src="{{ asset('assets/vendors/js/vendors.min.js') }}" type="text/javascript"></script>
{{-- <script src="{{asset('assets/vendors/js/forms/toggle/switchery.min.js')}}" type="text/javascript"></script> --}}
{{-- <script src="{{asset('assets/js/scripts/forms/switch.min.js')}}" type="text/javascript"></script> --}}
<script src="{{ asset('assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>
{{-- <script src="{{ asset('assets/vendors/js/ui/prism.min.js') }}" type="text/javascript"></script> --}}
<script src="{{ asset('assets/vendors/js/extensions/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/js/forms/select/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/js/pickers/dateTime/moment-with-locales.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('assets/vendors/js/pickers/daterange/daterangepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jqueryUi/jquery-ui.js') }}"></script>
{{-- <script src="{{asset('assets/vendors/js/forms/icheck/icheck.min.js')}}" type="text/javascript"></script> --}}
<script src="{{ asset('assets/vendors/js/animation/jquery.appear.js') }}" type="text/javascript"></script>
{{-- <script src="{{asset('assets/vendors/js/jquery.sharrre.js')}}" type="text/javascript"></script> --}}
<script src="{{ asset('assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}" type="text/javascript"></script>
 <script src="{{ asset('assets/js/jquery.timepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/js/editors/tinymce/tinymce.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/datetimepicker/build/jquery.datetimepicker.full.min.js') }}"></script>
@stack('vendors-js')
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
@stack('page-vendors-js')
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ asset('assets/js/core/app-menu.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/core/app.min.js') }}" type="text/javascript"></script>
@stack('theme-js')
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
{{-- <script src="{{asset('assets/js/scripts/forms/checkbox-radio.min.js')}}" type="text/javascript"></script> --}}
{{-- <script src="{{ asset('assets/js/scripts/forms/select/form-select2.min.js') }}" type="text/javascript"></script> --}}
{{-- <script src="{{ asset('assets/js/scripts/modal/components-modal.min.js') }}" type="text/javascript"></script> --}}
<script src="{{ asset('assets/js/scripts/animation/animation.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/jquery.mask.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2@11.js') }}"></script>
{{-- <script src="{{ asset('assets/vendors/js/forms/tags/tagging.min.js') }}" type="text/javascript"></script> --}}
{{-- <script src="{{ asset('assets/vendors/js/ui/prism.min.js') }}" type="text/javascript"></script> --}}
 <script src="{{ asset('assets/js/cropper.js') }}" type="text/javascript"></script>

<!-- Form Validation files - -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/es6-shim/0.35.3/es6-shim.min.js"></script>
<script src="{{ asset('formvalidation/dist/js/FormValidation.min.js') }}"></script>
<script src="{{ asset('formvalidation/dist/js/plugins/Bootstrap.min.js') }}"></script>
<script src="{{ asset('formvalidation/dist/js/plugins/Tachyons.min.js') }}"></script>
<script src="{{ asset('assets/imageUploader/image-uploader.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- <script src="{{ asset('js/sweetalert2all.js') }}" type="text/javascript"></script> --}}
<!-- END: Page JS-->

@stack('scripts')


<script>
    function cnfrmSwal(icon, title, text, route) {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                location.replace(route)
            }
        });
    }
    $(document).ready(function() {
        $('#Full_Loader').addClass('OFF');
        setTimeout(() => {
            $('#Full_Loader').addClass('OFF2');
        }, 1000);
    });
</script>
