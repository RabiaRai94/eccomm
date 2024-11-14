@extends('admin.dashboard.layout.master')

@section('content')
<h1 class="text-center mb-5">Product Categories</h1>

<div class="mb-3">
    <a href="{{ route('categories.create') }}" class="btn btn-success">Create New Category</a>
</div>

<table class="table" id="categoryTable">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
            <tr id="category_{{ $category->id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $category->name }}</td>
                <td>
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm" onclick="deleteCategory({{ $category->id }})">Delete</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category?')) {
        $.ajax({
            url: `/categories/${id}`,
            type: 'DELETE',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(response) {
                alert(response.message);
                $(`#category_${id}`).remove();
            },
            error: function() {
                alert('An error occurred');
            }
        });
    }
}
</script>
@endsection
