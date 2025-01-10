<?php

namespace App\Http\Controllers;

use Stripe\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
class WebhookController extends Controller
{
    /**
     * Handle incoming Stripe webhook events.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        // Retrieve the Stripe webhook secret from configuration
        $stripeWebhookSecret = config('services.stripe.webhook_secret');

        // Retrieve the Stripe-Signature header
        $signature = $request->header('Stripe-Signature');

        // Retrieve the raw payload
        $payload = $request->getContent();

        try {
            // Verify the webhook signature and parse the event
            $event = Webhook::constructEvent($payload, $signature, $stripeWebhookSecret);

            // Log the event type for debugging purposes
            Log::info('Stripe Webhook Event Received:', ['type' => $event->type]);

            // Handle the specific Stripe event
            return $this->handleStripeEvent($event);

        } catch (SignatureVerificationException $e) {
            // Log and return error response if signature is invalid
            Log::error('Stripe Webhook Signature Verification Failed:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);

        } catch (\UnexpectedValueException $e) {
            // Log and return error response if payload is invalid
            Log::error('Invalid Stripe Webhook Payload:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);
        }
    }

    /**
     * Handle specific Stripe webhook events.
     *
     * @param  object  $event
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleStripeEvent($event)
    {
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // Extract the payment intent object
                Log::info('Payment Intent Succeeded:', ['id' => $paymentIntent->id]);
                $this->processPaymentIntentSucceeded($paymentIntent);
                break;

            case 'charge.succeeded':
                $charge = $event->data->object; // Extract the charge object
                Log::info('Charge Succeeded:', ['id' => $charge->id]);
                $this->processChargeSucceeded($charge);
                break;

            // Add more event types as needed
            default:
                Log::warning('Unhandled Stripe Event:', ['type' => $event->type]);
                break;
        }

        // Always respond with 200 OK to acknowledge the event
        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Process a successful payment intent.
     *
     * @param  object  $paymentIntent
     */
    private function processPaymentIntentSucceeded($paymentIntent)
    {
        // Implement your logic here
        Log::info('Processing PaymentIntent Succeeded:', ['id' => $paymentIntent->id]);
        // Example: Update your database, mark the payment as complete, etc.
    }

    /**
     * Process a successful charge.
     *
     * @param  object  $charge
     */
    private function processChargeSucceeded($charge)
    {
        // Implement your logic here
        Log::info('Processing Charge Succeeded:', ['id' => $charge->id]);
        // Example: Update your database, mark the charge as successful, etc.
    }
}
