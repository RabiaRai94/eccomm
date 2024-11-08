

@section('content')
    <h1>All Users</h1>

    <a href="{{ route('users.create') }}">Create New User</a> <!-- Link to create new user -->

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user) <!-- Looping through each user -->
                <tr>
                    <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <!-- Link to show user details -->
                        <a href="{{ route('users.show', $user->id) }}">View User</a>

                        <!-- Link to edit user -->
                        <a href="{{ route('users.edit', $user->id) }}">Edit User</a>

                        <!-- Form to delete user -->
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete User</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
