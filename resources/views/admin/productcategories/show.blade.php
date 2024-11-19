@extends('admin.dashboard.layout.master')


@section('content')
<h1>User Details</h1>
<div class="card">
    <div class="card-body">
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
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
            <a href="{{ route('users.index') }}">Back to List</a>
        </div>
    </div>
</div>
@endsection