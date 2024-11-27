<!doctype html>
<html lang="en">
@include('admin.dashboard.layout.head')
@include('admin.dashboard.layout.script')
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
        @include('admin.dashboard.layout.navbar')

        <!-- Main Content Area -->
        <main class="col-md-9 col-lg-10 p-4">
            <!-- Include Navbar -->
            <!-- @include('admin.dashboard.layout.navbar') -->
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
            @yield('content')

            <!-- Include Footer -->
            @include('admin.dashboard.layout.footer')
        </main>
    </div>
    @include('admin.dashboard.layout.script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.add-to-cart').forEach(function(button) {
                button.addEventListener('click', function() {
                    const productId = button.getAttribute('data-product-id');
                    const variantId = button.getAttribute('data-variant-id');
                    const size = button.getAttribute('data-variant-size');
                    const price = button.getAttribute('data-variant-price');

                    fetch('{{ route("cart.add") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                variant_id: variantId,
                                size: size,
                                price: price
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                updateCartUI(data.cart);
                            } else {
                                alert('Failed to add product to cart.');
                            }
                        });
                });
            });
        });

        function updateCartUI(cart) {
            const cartContainer = document.querySelector('.header-cart-wrapitem');
            cartContainer.innerHTML = '';
            let total = 0;

            cart.forEach(item => {
                total += item.price * item.quantity;
                cartContainer.innerHTML += `
            <li class="header-cart-item flex-w flex-t m-b-12">
                <div class="header-cart-item-img">
                    <img src="${item.image}" alt="${item.name}">
                </div>
                <div class="header-cart-item-txt p-t-8">
                    <a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
                        ${item.name} - ${item.size}
                    </a>
                    <span class="header-cart-item-info">
                        ${item.quantity} x $${item.price}
                    </span>
                </div>
            </li>`;
            });

            document.querySelector('.header-cart-total').innerHTML = `Total: $${total.toFixed(2)}`;
        }
    </script>
    @endsection


</body>


</html>