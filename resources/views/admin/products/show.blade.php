@extends('layout.app')

@section('content')
    <h1>User Details</h1>

    <div>
        <strong>First Name:</strong> {{ $user->firstname }}
    </div>
    <div>
        <strong>Last Name:</strong> {{ $user->lastname }}
    </div>
    <div>
        <strong>Email:</strong> {{ $user->email }}
    </div>
    <div>
        <strong>Phone Number:</strong> {{ $user->phonenumber }}
    </div>
    <div>
        <strong>Address:</strong> {{ $user->address }}
    </div>
    <div>
        <strong>Postal Code:</strong> {{ $user->postal_code }}
    </div>
    <div>
        <strong>Status:</strong> {{ ucfirst($user->status) }}
    </div>

    <div>
        <a href="{{ route('users.edit', $user) }}">Edit</a>
        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
        <a href="{{ route('users.index') }}">Back to List</a>
    </div>
@endsection
