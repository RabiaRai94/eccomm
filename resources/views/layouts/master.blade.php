<!doctype html>
<html lang="en">
@include('layouts.head')

<body>



  <div class="container-fluid">
    <div class="row">
      @include('layouts.main-menu')
      

      <main class="container-fluid">
        @yield('content')
      </main>

    </div>
  </div>
  @include('layouts.footer')

</body>

</html>