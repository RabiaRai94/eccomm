@extends('admin.dashboard.layout.master')

@section('content')
<h1 class="text-center mb-5">Product Categories</h1>

<div class="mb-3">
    <a href="{{ route('categories.create') }}" class="btn btn-success">
        <i class="fa fa-plus"></i> Create New Category
    </a>
</div>

<table class="table table-bordered" id="categoryTable">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- DataTable will populate this dynamically -->
    </tbody>
</table>

<script>
$(document).ready(function () {
    $('#categoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('categories.index') }}", 
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
});

function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category?')) {
        $.ajax({
            url: `/categories/${id}`,
            type: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function (response) {
                alert(response.message);
                $('#categoryTable').DataTable().ajax.reload(); 
            },
            error: function (xhr) {
                alert(xhr.responseJSON.message || 'An error occurred');
            }
        });
    }
}
</script>
@endsection
