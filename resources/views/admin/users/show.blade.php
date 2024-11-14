@extends('admin.dashboard.layout.master')

@section('content')
<h1 class="text-center mb-5">User List</h1>

<div class="container">
    <table id="userTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>

<script>
$(document).ready(function() {
    $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('users.data') }}',
        columns: [
            { data: 'id' },
            { data: 'firstname' },
            { data: 'lastname' },
            { data: 'email' },
            { data: 'phonenumber' },
            { data: 'status' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });
});

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        $.ajax({
            url: '/users/' + userId,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#userTable').DataTable().ajax.reload();
                alert(response.message);
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    }
}
</script>
@endsection
