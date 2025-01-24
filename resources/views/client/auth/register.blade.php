@extends('landing.layouts.master')

@section('content')
<div class="container mt-5">
     <h1>Client Registration</h1>
     <form method="POST" action="{{ route('client.login') }}" id="guest-checkout-form">
          @csrf
          <div class="mb-3">
               <label for="name" class="form-label">Full Name</label>
               <input type="text" class="form-control" id="name" name="name" required placeholder="Enter your full name">
          </div>
          <div class="mb-3">
               <label for="email" class="form-label">Email Address</label>
               <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email">
          </div>
          <div class="mb-3">
               <label for="password" class="form-label">Password</label>
               <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
          </div>
          <div class="mb-3">
               <label for="password_confirmation" class="form-label">Confirm Password</label>
               <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Confirm your password">
          </div>

          <button type="submit" class="btn btn-primary">Register</button>
     </form>
</div>

<script>
    document.getElementById('guest-checkout-form').addEventListener('submit', async function (event) {
        event.preventDefault(); 
        const form = event.target;
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData,
            });

            if (!response.ok) {
                throw await response.json();
            }

            const result = await response.json();
            alert(result.message || 'Registration successful!');
        } catch (error) {
            const errors = error.errors || { error: ['An error occurred'] };
            alert(Object.values(errors).flat().join('\n'));
        }
    });
</script>
@endsection
