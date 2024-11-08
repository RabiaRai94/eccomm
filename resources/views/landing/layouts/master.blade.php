<!doctype html>
<html lang="en">
@include('landing.layouts.head')

<body>



  <div class="container-fluid">
    <div class="row">
      @include('landing.layouts.main-menu')
      

      <main class="container-fluid">
        @yield('content')
      </main>

    </div>
  </div>
  @include('landing.layouts.footer')

</body>

</html>