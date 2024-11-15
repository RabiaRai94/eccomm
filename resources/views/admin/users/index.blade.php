@extends('admin.dashboard.layout.master')

@section('content')
<h1 class="text-center mb-5">Users</h1>
<a href="{{ route('users.create') }}">Create New User</a>
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-10">
        <table id="usersTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Postal Code</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded here using AJAX -->
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.index') }}", 
            columns: [
                { data: 'id', name: 'id' },
                { data: 'firstname', name: 'firstname' },
                { data: 'lastname', name: 'lastname' },
                { data: 'email', name: 'email' },
                { data: 'phonenumber', name: 'phonenumber' },
                { data: 'address', name: 'address' },
                { data: 'postal_code', name: 'postal_code' },
                { data: 'status', name: 'status' },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                }
            ]
        });
    });
</script>
@endsection
