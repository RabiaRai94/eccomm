@extends('admin.dashboard.layout.master')

@section('content')
<h1 class="text-center mb-5">Product List</h1>

<div class="mb-3">
    <a href="{{ route('products.create') }}" class="btn btn-success">
        <i class="fa fa-plus"></i> Create New Product
    </a>
</div>

<table class="table table-bordered" id="productTable">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Description</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- DataTable will populate this dynamically -->
    </tbody>
</table>

<script>
$(document).ready(function () {
    $('#productTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('products.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'category.name', name: 'category.name' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
});
function deleteProduct(id) {
        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                url: `/products/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.message);
                    $(`#product_${id}`).remove();
                    $('#productTable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message || 'An error occurred');
                }
            });
        }
    }
</script>
@endsection
