@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <h3>Checkout</h3>
    <div id="payment-element"></div>
    <button id="submit" class="btn btn-primary mt-3">Pay</button>
    <div id="error-message" class="text-danger mt-2"></div> 
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialize Stripe with the publishable key
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    var elements = stripe.elements({
        clientSecret: 'CLIENT_SECRET',
      });
    // Fetch clientSecret from the backend
    fetch("{{ route('payment.intent') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error('Error:', data.error);
            document.getElementById('error-message').textContent = data.error;
            return;
        }

        // Pass the valid clientSecret to Stripe Elements
        const elements = stripe.elements({ clientSecret: data.clientSecret });

        // Create and mount the Payment Element
        const paymentElement = elements.create('payment');
        paymentElement.mount('#payment-element');

        // Handle form submission
        document.getElementById('submit').addEventListener('click', async () => {
            const { error } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: "{{ route('payment.success') }}", // Set your success route
                },
            });

            if (error) {
                document.getElementById('error-message').textContent = error.message;
            }
        });
    })
    .catch(error => {
        console.error('Error fetching clientSecret:', error);
        document.getElementById('error-message').textContent = 'Failed to initialize payment.';
    });
</script>

@endsection
