<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page Products</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        #products-table_wrapper {
            width: 100%;
        }

        .dataTables_wrapper .dataTables_processing {
            top: 50%;
            transform: translateY(-50%);
        }

        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .category-buttons {
            text-align: center;
            margin-bottom: 20px;
        }

        .category-buttons button {
            margin: 5px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1 class="text-center">All Products</h1>

        <div class="category-buttons">
        </div>

        <div id="products-container" class="cards-container">
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '{{ route("landing.categories") }}',
                method: 'GET',
                success: function(categories) {
                    const buttonsContainer = $('.category-buttons');
                    buttonsContainer.append('<button class="btn btn-secondary" data-category="all">All</button>');
                    categories.forEach(category => {
                        buttonsContainer.append(
                            `<button class="btn btn-secondary" data-category="${category.id}">${category.name}</button>`
                        );
                    });
                    loadProducts();
                }
            });

            $(document).on('click', '.category-buttons button', function() {
                const categoryId = $(this).data('category');
                loadProducts(categoryId);
            });



            function loadProducts(categoryId = 'all') {
                $.ajax({
                    url: '{{ route("landing.products.data") }}',
                    method: 'GET',
                    data: {
                        category: categoryId
                    },
                    success: function(products) {
                        const container = $('#products-container');
                        container.empty();
                        products.forEach(product => {
                            container.append(product.card);
                        });
                    }
                });
            }
        });
        $(document).on('click', '.add-to-cart', function() {
            let productId = $(this).data('product-id');
            let variantId = $(this).data('variant-id');
            const quantity = $(this).data('quantity') || 1;

            $.ajax({
                url: '/cart/add',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    variant_id: variantId,
                    quantity: quantity
                },
                success: function(response) {
                    alert(response.message);
                    const cartCount = response.cartCount;
                    $('.js-show-cart').attr('data-notify', cartCount);
                },
                error: function() {
                    alert('Failed to add product to cart/out of stock.');
                }
            });
        });
    </script>
</body>

</html>