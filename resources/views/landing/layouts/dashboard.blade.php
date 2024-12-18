<!DOCTYPE html>
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
     <div class="wrapper d-flex w-100">
          <!-- Sidebar -->
          <div class="sidebar p-4">
               <h4>Admin Dashboard</h4>
               @include('admin.dashboard.layout.navbar')

          </div>

          <!-- Content -->
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
          </div>
     </div>
   

</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
     function toggleSubMenu(event) {
          event.preventDefault();
          const parentItem = event.currentTarget.parentNode;
          const isActive = parentItem.classList.contains('active');

          document.querySelectorAll('#sidebarMenu .nav-item').forEach(item => {
               item.classList.remove('active');
          });

          if (!isActive) {
               parentItem.classList.add('active');
          }
     }

     document.addEventListener('DOMContentLoaded', function() {
          const settingsLink = document.querySelector('.settings-link');
          if (settingsLink) {
               settingsLink.addEventListener('click', toggleSubMenu);
          }
     });

     function changeColor(link) {
          var navLinks = document.querySelectorAll('#sidebarMenu .nav-link');
          navLinks.forEach(function(navLink) {
               navLink.classList.remove('active');
               navLink.style.color = '';
          });
          link.classList.add('active');
          link.style.color = 'white';
     }
</script>