@extends('layout.app')

@section('content')
    <h1>Edit User</h1>

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="firstname">First Name:</label>
            <input type="text" name="firstname" id="firstname" value="{{ old('firstname', $user->firstname) }}" required>
            @error('firstname')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="lastname">Last Name:</label>
            <input type="text" name="lastname" id="lastname" value="{{ old('lastname', $user->lastname) }}">
            @error('lastname')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password">Password (leave blank to keep current password):</label>
            <input type="password" name="password" id="password">
            @error('password')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="phonenumber">Phone Number:</label>
            <input type="text" name="phonenumber" id="phonenumber" value="{{ old('phonenumber', $user->phonenumber) }}">
            @error('phonenumber')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="address">Address:</label>
            <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}">
            @error('address')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="postal_code">Postal Code:</label>
            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
            @error('postal_code')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Update User</button>
    </form>
@endsection
