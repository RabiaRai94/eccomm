@extends('landing.layouts.master')
@section('content')

<div class="container mt-5">
    <h3>Checkout</h3>
    <form action="{{ route('payment.process') }}" method="POST" id="payment-form">
        @csrf
        <input type="hidden" name="price" value="{{ number_format($total, 2) }}">
        <input type="hidden" name="stripeToken" id="stripe-Token">
        <div id="card-element" class="form-control"></div>
        <button type="submit" id="pay-button" class="btn btn-primary">Pay</button>
        <div id="error-message" class="text-danger mt-2"></div>
    </form>

</div>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('pk_test_51QSENdCOqW9AveYvKJOChNGDTGmKK0oiLlTofOPN9So3sIFpRjQ33h2wTfpQsT1MkG6X7y6S2RF2lw1a7b5dyOZY00OCn6ZRRX');
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');



    document.getElementById('pay-button').addEventListener('click', async (event) => {
        event.preventDefault();

        console.log("Pay button clicked");

        const {
            error,
            paymentIntent
        } = await stripe.confirmCardPayment(
            "{{ $clientSecret }}", {
                payment_method: {
                    card: cardElement,
                },
            }
        );

        if (error) {
            console.log("Error during payment confirmation:", error.message);
            document.getElementById('error-message').textContent = error.message;
        } else if (paymentIntent && paymentIntent.status === 'succeeded') {
            console.log("Payment succeeded:", paymentIntent.id);

            document.getElementById('stripe-Token').value = paymentIntent.id;
            console.log("Submitting the form now...");
            document.getElementById('payment-form').submit();

        }
    });
</script>
@endsection