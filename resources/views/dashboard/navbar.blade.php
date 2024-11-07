
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
</style>


<nav id="sidebarMenu" class="bg-gray-900 text-white h-full">
    <div class="pt-4">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('dashboard') }}" onclick="changeColor(this)">
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
            <li class="nav-item">
                <a class="nav-link settings-link">
                    <i class="fas fa-cog"></i> Settings
                    <i class="fas fa-chevron-down submenu-icon"></i>
                </a>
                <ul class="sub-menu">
                    <li><a href="index.html">Store Details</a></li>
                    <li><a href="home-02.html">Payment</a></li>
                    <li><a href="home-03.html">Checkout</a></li>
                    <li><a href="home-03.html">Shipping and Delivery</a></li>
                    <li><a href="home-03.html">Locations</a></li>
                    <li><a href="home-03.html">Notifications</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

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
</section>