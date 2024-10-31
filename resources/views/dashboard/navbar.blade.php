<style>
     #sidebarMenu {
          position: fixed;
          top: 0;
          left: 0;
          height: 100vh;
          width: 250px;
          background-color: #343a40;
          padding-top: 20px;
          color: #fff;
          transition: width 0.3s ease;
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
     }

     #sidebarMenu.collapsed {
          width: 80px;
     }

     #sidebarMenu.collapsed .nav-link {
          justify-content: center;
     }

     #sidebarMenu.collapsed .nav-link span {
          display: none;
     }

   
     .sub-menu {
          list-style: none;
          padding-left: 20px;
       
          margin: 0;
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
          transition: max-height 0.3s ease;
     }
</style>

<nav id="sidebarMenu" class="bg-grey sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('dashboard') }}" onclick="changeColor(this)">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" onclick="changeColor(this)">
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
                    <li><a href="index.html">Store Detatils</a></li>
                    <li><a href="home-02.html">Payment</a></li>
                    <li><a href="home-03.html">Chackout</a></li>
                    <li><a href="home-03.html">Shipping and delivery</a></li>
                    <li><a href="home-03.html">Locations</a></li>
                    <li><a href="home-03.html">Notifications</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebarMenu');
        sidebar.classList.toggle('collapsed');
    }

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

    function changeColor(link) {
        var navLinks = document.querySelectorAll('#sidebarMenu .nav-link');

        navLinks.forEach(function (navLink) {
            navLink.classList.remove('active');
            navLink.style.color = '';
        });

        link.classList.add('active');
        link.style.color = 'blue';
    }
</script>
