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

          .product-card {
               border: 1px solid #ddd;
               border-radius: 5px;
               padding: 10px;
               margin: 10px;
               background-color: #f9f9f9;
               width: 300px;
               text-align: center;
               display: inline-block;
               vertical-align: top;
          }

          .variant-card {
               margin-top: 10px;
               padding: 5px;
               border: 1px solid #ccc;
               border-radius: 3px;
               background-color: #fff;
          }

          .cards-container {
               display: flex;
               flex-wrap: wrap;
               gap: 20px;
               justify-content: center;
          }

          #products-table {
               border: none;
               width: 100%;
          }

          #products-table tbody tr {
               display: flex;
               justify-content: center;
          }

          #products-table tbody td {
               border: none;
               padding: 0;
          }

          .card-img-top img {
               width: 100%;
               height: 150px;
               object-fit: cover;
               border-radius: 5px;
          }
     </style>
</head>

<body>
     <div class="container mt-4">
          <h1 class="text-center">All Products</h1>
          <table id="products-table" class="display nowrap" style="width: 100%;">
               <thead>
                    <tr>
                         <th>Products</th>
                    </tr>
               </thead>
          </table>
     </div>

     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
     <script>
          $(document).ready(function() {
               $('#products-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route("landing.products.data") }}',
                    columns: [{
                         data: 'card',
                         name: 'card',
                         orderable: false,
                         searchable: false,
                    }, ],
                    drawCallback: function() {
                         $('#products-table tbody').addClass('cards-container');
                    },
                    paging: true,
                    pageLength: 10,
               });
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