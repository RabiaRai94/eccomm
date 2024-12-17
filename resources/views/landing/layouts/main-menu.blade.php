<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
     body,
     html {
          margin: 0;
          padding: 0;
          height: 100%;
     }

     .container-menu-desktop {
          position: relative;
          width: 100%;
     }

     .top-bar {
          background-color: #333;
          color: white;
          padding: 10px 0;
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          z-index: 10;
          transition: top 0.3s ease-in-out;
     }

     .content-topbar {
          display: flex;
          justify-content: space-between;
          align-items: center;
          padding: 0 20px;
     }

     .wrap-menu-desktop {
          position: fixed;
          top: 35px;
          left: 0;
          width: 100%;
          z-index: 5;
          background-color: white;
          box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
     }

     /* .container-menu-desktop {
          margin-top: 100px;
     } */

     .menu-desktop {
          display: flex;
          justify-content: space-between;
          align-items: center;
          padding: 10px 20px;
     }

     .icon-header-item {
          font-size: 20px;
          margin-right: 10px;
     }

     .icon-header-item:hover {
          cursor: pointer;
     }
</style>


<header>
     <div class="container-menu-desktop mb-5 pb-5">
          <div class="top-bar">
               <div class="content-topbar flex-sb-m h-full container">
                    <div class="left-top-bar">
                         Free shipping for standard order over $100
                    </div>
                    <div class="right-top-bar flex-w h-full">
                         <a href="#" class="flex-c-m trans-04 p-lr-25">
                              Help & FAQs
                         </a>

                         <a href="#" class="flex-c-m trans-04 p-lr-25">
                              My Account
                         </a>

                         <a href="#" class="flex-c-m trans-04 p-lr-25">
                              EN
                         </a>

                         <a href="#" class="flex-c-m trans-04 p-lr-25">
                              USD
                         </a>
                    </div>
               </div>
          </div>

          <div class="wrap-menu-desktop">
               <nav class="limiter-menu-desktop container">

                    <!-- Logo desktop -->
                    <a href="{{ route('home') }}" class="logo">
                         <img src="images/icons/logo-01.png" alt="IMG-LOGO">
                    </a>

                    <!-- Menu desktop -->
                    <div class="menu-desktop">
                         <ul class="main-menu">
                              <li class="active-menu">
                                   <a href="{{ route('home') }}">Home</a>
                                   <ul class="sub-menu">
                                        <li><a href="index.html">Homepage 1</a></li>
                                        <li><a href="home-02.html">Homepage 2</a></li>
                                        <li><a href="home-03.html">Homepage 3</a></li>
                                   </ul>
                              </li>

                              <li>
                                   <a href="{{ route('shopproducts') }}">Shop</a>
                              </li>

                              <li class="label1" data-label1="hot">
                                   <a href="{{ route('shopping-cart') }}">Shopping-Cart</a>
                              </li>

                              <li>
                                   <a href="{{ route('blogs') }}">Blog</a>
                              </li>

                              <li>
                                   <a href="{{ route('about-us') }}">About</a>
                              </li>

                              <li>
                                   <a href="{{ route('contact-us') }}">Contact</a>
                              </li>
                              <li>
                                   <a href="{{ route('login') }}">Login</a>
                              </li>
                              <li>
                                   <a href="{{ route('register') }}">Register</a>
                              </li>


                         </ul>
                    </div>

                    <!-- Icon header -->
                    <div class="wrap-icon-header flex-w flex-r-m">
                         <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
                              <i class="fas fa-search"></i>
                         </div>
                         <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart" data-notify="{{ $cartCount ?? 0 }}">
                              <i class="fas fa-shopping-cart"></i>
                         </div>

                         <a href="#" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti" data-notify="0">
                              <i class="fas fa-heart"></i>
                         </a>
                    </div>

               </nav>
          </div>
     </div>

     <div class="wrap-header-mobile">
          <div class="logo-mobile">
               <a href="index.html"><img src="images/icons/logo-01.png" alt="IMG-LOGO"></a>
          </div>
          <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
               <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
               </span>
          </div>
     </div>

     <div class="menu-mobile">
          <ul class="topbar-mobile">
               <li>
                    <div class="left-top-bar">
                         Free shipping for standard order over $100
                    </div>
               </li>

               <li>
                    <div class="right-top-bar flex-w h-full">
                         <a href="#" class="flex-c-m p-lr-10 trans-04">
                              Help & FAQs
                         </a>

                         <a href="#" class="flex-c-m p-lr-10 trans-04">
                              My Account
                         </a>

                         <a href="#" class="flex-c-m p-lr-10 trans-04">
                              EN
                         </a>

                         <a href="#" class="flex-c-m p-lr-10 trans-04">
                              USD
                         </a>
                    </div>
               </li>
          </ul>

          <ul class="main-menu-m">
               <li>
                    <a href="{{ route('home') }}">Home</a>
                    <ul class="sub-menu-m">
                         <li><a href="{{ route('home') }}">Homepage 1</a></li>
                         <li><a href="{{ route('home') }}">Homepage 2</a></li>
                    </ul>
                    <span class="arrow-main-menu-m">
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </span>
               </li>

               <li>
                    <a href="{{ route('shopproducts') }}">Shop</a>
               </li>

               <li>
                    <a href="{{ route('shopping-cart') }}" class="label1 rs1" data-label1="hot">Shopping Cart</a>
               </li>

               <li>
                    <a href="{{ route('blogs') }}">Blog</a>
               </li>

               <li>
                    <a href="{{ route('about-us') }}">About</a>
               </li>
          </ul>
     </div>

     <div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
          <div class="container-search-header">
               <button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
                    <img src="images/icons/icon-close2.png" alt="CLOSE">
               </button>

               <form class="wrap-search-header flex-w p-l-15">
                    <button class="flex-c-m trans-04">
                         <i class="zmdi zmdi-search"></i>
                    </button>
                    <input class="plh3" type="text" name="search" placeholder="Search...">
               </form>
          </div>
     </div>

</header>
<script>
     let topBar = document.querySelector('.top-bar');
     let navbar = document.querySelector('.wrap-menu-desktop');
     let prevScrollPos = window.pageYOffset;

     window.onscroll = function() {
          let currentScrollPos = window.pageYOffset;

          if (prevScrollPos > currentScrollPos) {
               topBar.style.top = "0";
               navbar.style.top = "30px";
          } else {
               topBar.style.top = "-50px";
               navbar.style.top = "0";
          }

          prevScrollPos = currentScrollPos;
     };
</script>