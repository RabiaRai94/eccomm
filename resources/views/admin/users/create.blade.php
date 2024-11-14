@extends('admin.dashboard.layout.master')

@section('content')
<h1 class="text-center mb-5">Create New User</h1>

<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-6">
        <form id="ajaxForm" class="p-4 border rounded shadow-sm bg-white" style="border-radius: 12px;">
            @csrf

          
            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" name="firstname" id="firstname" class="form-control" required>
                <div id="firstname_error" class="text-danger"></div>
            </div>

            <div class="mb-3">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" name="lastname" id="lastname" class="form-control">
                <div id="lastname_error" class="text-danger"></div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
                <div id="email_error" class="text-danger"></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
                <div id="password_error" class="text-danger"></div>
            </div>

            <div class="mb-3">
                <label for="phonenumber" class="form-label">Phone Number</label>
                <input type="text" name="phonenumber" id="phonenumber" class="form-control">
                <div id="phonenumber_error" class="text-danger"></div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" id="address" class="form-control">
                <div id="address_error" class="text-danger"></div>
            </div>

            <div class="mb-3">
                <label for="postal_code" class="form-label">Postal Code</label>
                <input type="text" name="postal_code" id="postal_code" class="form-control">
                <div id="postal_code_error" class="text-danger"></div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <div id="status_error" class="text-danger"></div>
            </div>

          
            <div class="mb-3 row">
                <div class="col-md-6">
                    <label for="profile_picture">Profile Picture</label>
                    <input type="file" id="profile_picture" name="profile_picture" class="form-control">
                </div>
            </div>
            <button type="button" class="btn btn-primary w-100 mt-3" onclick="submitAjaxForm()">Create User</button>
        </form>
    </div>
</div>

<div id="ajaxResponse" class="mt-3 text-center"></div>

<script>
    function submitAjaxForm() {
        let formData = new FormData(document.getElementById('ajaxForm'));

        fetch("{{ route('users.store') }}", {
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
            
            document.getElementById('ajaxForm').reset();
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
