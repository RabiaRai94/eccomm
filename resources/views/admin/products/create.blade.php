@extends('admin.dashboard.layout.master')

@section('content')
<h1 class="text-center mb-5">Create New Product</h1>

<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-6">
        <form id="productForm" class="p-4 border rounded shadow-sm bg-white" style="border-radius: 12px;">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
                <div id="name_error" class="text-danger"></div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4"></textarea>
                <div id="description_error" class="text-danger"></div>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <option value="" selected disabled>Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <div id="category_id_error" class="text-danger"></div>
            </div>

            <button type="button" class="btn btn-primary w-100 mt-3" onclick="submitProductForm()">Create Product</button>
        </form>
    </div>
</div>

<div id="ajaxResponse" class="mt-3 text-center"></div>

<script>
    function submitProductForm() {
        let formData = new FormData(document.getElementById('productForm'));

        fetch("{{ route('products.store') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw response;
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('ajaxResponse').innerHTML = `<div class="alert alert-success">${data.message}</div>`;
            document.getElementById('productForm').reset();
        })
        .catch(error => {
            if (error.status === 422) {
                error.json().then(errors => {
                    Object.keys(errors.errors).forEach((key) => {
                        let errorDiv = document.getElementById(`${key}_error`);
                        if (errorDiv) {
                            errorDiv.innerText = errors.errors[key][0];
                        }
                    });
                });
            } else {
                document.getElementById('ajaxResponse').innerHTML = `<div class="alert alert-danger">Something went wrong. Please try again.</div>`;
            }
        });
    }
</script>
@endsection
