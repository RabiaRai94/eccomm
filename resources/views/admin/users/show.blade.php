@extends('admin.dashboard.layout.master')
@section('content')
<h1>User Details</h1>
<div class="card p-2">
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
            <div>
                <strong>Address:</strong> {{ $user->address }}
            </div>
            <div>
                <strong>Postal Code:</strong> {{ $user->postal_code }}
            </div>
            <div>
                <strong>Status:</strong> {{ ucfirst($user->status) }}
            </div>
        </div>
    </div>
    <div class="d-flex gap-2">
    <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">
        <i class="fas fa-eye"></i> 
    </a>
    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">
        <i class="fas fa-edit"></i> 
    </a>
    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-trash-alt"></i>
        </button>
    </form>
</div>
</div>
<div>

    <a href="{{ route('users.index') }}">Back to List</a>

</div>
@endsection