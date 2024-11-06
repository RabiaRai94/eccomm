@extends('layouts.app')

@section('content')
    <h1>Create New User</h1>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div>
            <label for="firstname">First Name:</label>
            <input type="text" name="firstname" id="firstname" value="{{ old('firstname') }}" required>
            @error('firstname')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="lastname">Last Name:</label>
            <input type="text" name="lastname" id="lastname" value="{{ old('lastname') }}">
            @error('lastname')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            @error('email')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            @error('password')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="phonenumber">Phone Number:</label>
            <input type="text" name="phonenumber" id="phonenumber" value="{{ old('phonenumber') }}">
            @error('phonenumber')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="address">Address:</label>
            <input type="text" name="address" id="address" value="{{ old('address') }}">
            @error('address')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="postal_code">Postal Code:</label>
            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}">
            @error('postal_code')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Create User</button>
    </form>
@endsection
