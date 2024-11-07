<!-- Include jQuery -->

<script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
<!-- Include Popper.js -->

<!-- Include Bootstrap JS -->
<!-- <script src="{{asset('assets/bootstrap/bootstrap.min.js')}}"></script> -->

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Include DataTables JS -->


<script src="{{asset('assets/DataTables/dataTables.js')}}"></script>
<script src="{{asset('assets/DataTables/datatables.min.js')}}" defer></script>

<script src="{{ asset('assets/selects/js/select2.full.min.js') }}" type="text/javascript"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Include DataTables Bootstrap 4 Integration JS -->
<!-- Feather Icons -->
<script src="{{asset('assets/feathericon/all.min.js')}}"></script>
<script src="{{asset('assets/js/pusher.min.js')}}"></script>
<script src="{{asset('assets/js/slick.custom.js')}}"></script>
<script src="{{asset('assets/js/main.js')}}"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>

<script>
        var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
                cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}"
        });
</script>

<script type="text/javascript" src="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
