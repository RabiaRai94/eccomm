<!-- <!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Admin Dashboard - E-commerce</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     <style>
          body {
               display: flex;
               min-height: 100vh;
          }

          .sidebar {
               width: 250px;
               background-color: #343a40;
               color: white;
               flex-shrink: 0;
          }

          .sidebar .nav-link {
               color: #ffffff;
          }

          .content {
               flex: 1;
               padding: 20px;
               background-color: #f8f9fa;
          }
     </style>
</head>

<body>
     <div class=" w-100">
          <div class="wrapper d-flex w-100">
               <div class="sidebar p-4">
                    <h4>Admin Dashboard</h4>
                    @include('dashboard.navbar')

               </div>

               <div class="content">
                    <!-- Navbar -->
                    <nav class="navbar navbar-light bg-light justify-content-between mb-4">
                         <a class="navbar-brand">E-commerce Admin</a>
                         <form method="POST" action="{{ route('logout') }}">
                              @csrf

                              <x-dropdown-link :href="route('logout')"
                                   onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                   {{ __('Log Out') }}
                              </x-dropdown-link>
                         </form>
                    </nav>

                    <!-- Dashboard Content -->
                    <div class="container">
                         @yield('content')
                    </div>
               <!-- </div>
          </div>
          <div>
               @include('layouts.footer')
          </div>
     </div>
</body>

</html> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --> -->
<!doctype html>
<html lang="en">
@include('layouts.head')

<body>



  <div class="container-fluid">
    <div class="row">
      @include('admin.dashboard.layout.navbar')

      <main class="container-fluid">
        @yield('content')
      </main>

    </div>
  </div>
  @include('layouts.footer')

</body>

</html>
