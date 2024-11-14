@extends('admin.dashboard.layout.master')
@section('content')
<h1 class="text-center mb-5">Update User</h1>

<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-6">
        <form id="ajaxUpdateForm" class="p-4 border rounded shadow-sm bg-white" style="max-width: 100%; width: 100%; border-radius: 12px;">
            @csrf
            @method('PUT') <!-- To support PUT method for updating -->

            <!-- First Name -->
            <div class="mb-3">
                <label for="firstname_ajax" class="form-label">First Name</label>
                <input type="text" name="firstname" id="firstname_ajax" class="form-control" value="{{ $user->firstname }}" required>
                <div id="firstname_ajax_error" class="text-danger"></div>
            </div>

            <!-- Last Name -->
            <div class="mb-3">
                <label for="lastname_ajax" class="form-label">Last Name</label>
                <input type="text" name="lastname" id="lastname_ajax" class="form-control" value="{{ $user->lastname }}">
                <div id="lastname_ajax_error" class="text-danger"></div>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email_ajax" class="form-label">Email</label>
                <input type="email" name="email" id="email_ajax" class="form-control" value="{{ $user->email }}" required>
                <div id="email_ajax_error" class="text-danger"></div>
            </div>

            <!-- Phone Number -->
            <div class="mb-3">
                <label for="phonenumber_ajax" class="form-label">Phone Number</label>
                <input type="text" name="phonenumber" id="phonenumber_ajax" class="form-control" value="{{ $user->phonenumber }}">
                <div id="phonenumber_ajax_error" class="text-danger"></div>
            </div>

            <!-- Address -->
            <div class="mb-3">
                <label for="address_ajax" class="form-label">Address</label>
                <input type="text" name="address" id="address_ajax" class="form-control" value="{{ $user->address }}">
                <div id="address_ajax_error" class="text-danger"></div>
            </div>

            <!-- Postal Code -->
            <div class="mb-3">
                <label for="postal_code_ajax" class="form-label">Postal Code</label>
                <input type="text" name="postal_code" id="postal_code_ajax" class="form-control" value="{{ $user->postal_code }}">
                <div id="postal_code_ajax_error" class="text-danger"></div>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status_ajax" class="form-label">Status</label>
                <select name="status" id="status_ajax" class="form-select">
                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <div id="status_ajax_error" class="text-danger"></div>
            </div>

            <!-- Update Button -->
            <button type="button" class="btn btn-primary w-100 mt-3" style="padding: 10px; font-size: 16px;" onclick="submitAjaxUpdateForm()">Update User</button>
        </form>
    </div>
</div>

<div id="ajaxResponse" class="mt-3 text-center"></div>

<script>
    function submitAjaxUpdateForm() {
        let formData = new FormData(document.getElementById('ajaxUpdateForm'));

        fetch("{{ route('users.update', $user->id) }}", {
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
            // Redirect to users.index page on success
            window.location.href = "{{ route('users.index') }}";
        })
        .catch(error => {
            if (error.status === 422) {
                error.json().then(errors => {
                    // Show validation errors
                    Object.keys(errors.errors).forEach((key) => {
                        let errorDiv = document.getElementById(`${key}_ajax_error`);
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
