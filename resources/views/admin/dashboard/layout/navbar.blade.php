<section>
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

        .submenu-icon {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .nav-item.active .submenu-icon {
            transform: rotate(180deg);
        }
        .dropdown-menu{
            background-color: #000000;
        }
        .dropdown-item{
            color: #adb5bd;
        }
        
    </style>

    <nav id="sidebarMenu" class="bg-gray-900 text-white h-full">
        <div class="pt-4">
            <ul>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}" onclick="changeColor(this)">
                        <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('users.index') }}" onclick="changeColor(this)">
                        <i class="fas fa-home"></i> <span>Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}" onclick="changeColor(this)">
                        <i class="fas fa-home"></i> <span>Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('categories.index') }}" onclick="changeColor(this)">
                        <i class="fas fa-home"></i> <span>Product Categories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" onclick="changeColor(this)">
                        <i class="fa-brands fa-critical-role"></i> <span>Order</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" onclick="changeColor(this)">
                        <i class="fas fa-users"></i> <span>Customers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" onclick="changeColor(this)">
                        <i class="fas fa-folder"></i> <span>Manage Reviews</span>
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog"></i> Settings
                    
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="settingsDropdown">
                        <li><a class="dropdown-item" href="index.html">Store Details</a></li>
                        <li><a class="dropdown-item" href="home-02.html">Payment</a></li>
                        <li><a class="dropdown-item" href="home-03.html">Checkout</a></li>
                        <li><a class="dropdown-item" href="home-03.html">Shipping and Delivery</a></li>
                        <li><a class="dropdown-item" href="home-03.html">Locations</a></li>
                        <li><a class="dropdown-item" href="home-03.html">Notifications</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <script>
         function changeColor(link) {
            var navLinks = document.querySelectorAll('#sidebarMenu .nav-link');
            navLinks.forEach(function(navLink) {
                navLink.classList.remove('active');
                navLink.style.color = '';
            });
            link.classList.add('active');
            link.style.color = 'black';
        }
    </script>
</section>