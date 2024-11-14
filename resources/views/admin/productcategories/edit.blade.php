@extends('admin.dashboard.layout.master')

@section('content')
<h1 class="text-center mb-5">Edit Category</h1>

<div class="col-md-6 offset-md-3">
    <form id="categoryForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="category_id" value="{{ $category->id }}">
        <div class="mb-3">
            <label for="name">Category Name:</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $category->name }}" required>
            <div id="name_error" class="text-danger"></div>
        </div>
        <button type="button" class="btn btn-primary" onclick="submitCategory()">Update Category</button>
    </form>
</div>

<script>
function submitCategory() {
    const formData = new FormData(document.getElementById('categoryForm'));
    const categoryId = '{{ $category->id }}';

    $.ajax({
        url: `/categories/${categoryId}`,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        success: function(response) {
            alert(response.message);
            window.location.href = "{{ route('categories.index') }}";
        },
        error: function(xhr) {
            let errors = xhr.responseJSON.errors;
            $('#name_error').text(errors ? errors.name : 'An error occurred');
        }
    });
}
</script>
@endsection
