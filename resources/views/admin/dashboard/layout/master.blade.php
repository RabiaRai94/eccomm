<!doctype html>
<html lang="en">
@include('admin.dashboard.layout.head')
<style>
        #sidebarMenu {
            width: 250px;
            background-color: #343a40;
            color: #fff;
            transition: width 0.3s ease;
            position: relative;
        }

        #sidebarMenu .nav-link {
            color: #adb5bd;
            font-size: 16px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: color 0.2s, background 0.2s;
        }

        #sidebarMenu .nav-link:hover {
            color: #ffffff;
            background-color: #495057;
        }

        #sidebarMenu .nav-link.active,
        #sidebarMenu .nav-link.active:hover {
            color: #ffffff;
            background-color: #9099a7;
        }

        #sidebarMenu .nav-link i {
            font-size: 1.2em;
        }

        #sidebarMenu .nav-item {
            margin-bottom: 5px;
            list-style: none;
        }

        .sub-menu {
            padding-left: 20px;
            display: none;
            background-color: #343a40;
        }

        .sub-menu li a {
            display: block;
            padding: 10px 20px;
            color: #adb5bd;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s, background 0.2s;
        }

        .sub-menu li a:hover {
            background-color: #495057;
            color: #ffffff;
        }

        .nav-item.active>.sub-menu {
            display: block;
        }
    </style>
<body>
    <div class="d-flex" style="min-height: 100vh;">
        <!-- Sidebar -->
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-gray-900 text-white p-3">
            <div class="pt-4">
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link active text-white" href="{{ route('admin.dashboard') }}" onclick="changeColor(this)">
                            <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="{{ route('users.index') }}" onclick="changeColor(this)">
                            <i class="fas fa-users"></i> <span>Users</span>
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="{{ route('products.index') }}" onclick="changeColor(this)">
                            <i class="fas fa-box"></i> <span>Products</span>
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" onclick="changeColor(this)">
                            <i class="fas fa-shopping-cart"></i> <span>Orders</span>
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" onclick="changeColor(this)">
                            <i class="fas fa-user-friends"></i> <span>Customers</span>
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" onclick="changeColor(this)">
                            <i class="fas fa-comments"></i> <span>Manage Reviews</span>
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link settings-link text-white">
                            <i class="fas fa-cog"></i> Settings
                            <i class="fas fa-chevron-down submenu-icon"></i>
                        </a>
                        <!-- Submenu -->
                        <ul class="sub-menu ms-3">
                            <li><a href="#" class="text-white">Store Details</a></li>
                            <li><a href="#" class="text-white">Payment</a></li>
                            <li><a href="#" class="text-white">Checkout</a></li>
                            <li><a href="#" class="text-white">Shipping & Delivery</a></li>
                            <li><a href="#" class="text-white">Locations</a></li>
                            <li><a href="#" class="text-white">Notifications</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="col-md-9 col-lg-10 p-4">
            <!-- Include Navbar -->
            <!-- @include('admin.dashboard.layout.navbar') -->

            <!-- Dashboard Content -->
            @yield('content')

            <!-- Include Footer -->
            @include('admin.dashboard.layout.footer')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle submenu functionality
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

        document.addEventListener('DOMContentLoaded', function () {
            const settingsLink = document.querySelector('.settings-link');
            if (settingsLink) {
                settingsLink.addEventListener('click', toggleSubMenu);
            }
        });

        // Change active link color
        function changeColor(link) {
            var navLinks = document.querySelectorAll('#sidebarMenu .nav-link');
            navLinks.forEach(function (navLink) {
                navLink.classList.remove('active');
                navLink.style.color = '';
            });
            link.classList.add('active');
            link.style.color = 'white';
        }
    </script>
</body>


</html>