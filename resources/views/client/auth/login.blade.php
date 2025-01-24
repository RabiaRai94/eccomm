@extends('landing.layouts.master')

@section('content')
<div class="container mt-5">
    <h3>Customer Login</h3>
    <form method="POST" action="{{ route('client.login.submit') }}" id="customer-login-form">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<script>
    document.getElementById('customer-login-form').addEventListener('submit', async function(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData,
            });

            if (!response.ok) {
                throw await response.json();
            }

            const result = await response.json();
            alert(result.message || 'Login successful!');
            window.location.href = result.redirect || '/dashboard';
        } catch (error) {
            const errors = error.errors || {
                error: ['Invalid credentials']
            };
            alert(Object.values(errors).flat().join('\n'));
        }
    });
</script>
@endsection