@extends('admin.dashboard.layout.master')

@section('content')
<h1 class="text-center mb-5">
    {{ isset($category) ? 'Edit Category' : 'Create New Category' }}
</h1>

<div class="col-md-6 offset-md-3">
    <form id="categoryForm" method="POST" action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}">
        @csrf
        @if (isset($category))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="name">Category Name:</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ isset($category) ? $category->name : '' }}" required>
            <div id="name_error" class="text-danger"></div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-3">
            {{ isset($category) ? 'Update Category' : 'Save Category' }}
        </button>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#categoryForm').on('submit', function(event) {
        event.preventDefault();
        
        const formData = new FormData($(this)[0]);
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response.message);  
                window.location.href = "{{ route('categories.index') }}";  
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.errors;
                $('#name_error').text(errors ? errors.name : 'An error occurred');
            }
        });
    });
});
</script>
@endsection
