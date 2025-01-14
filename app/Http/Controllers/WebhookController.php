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
        $stripeWebhookSecret = config('services.stripe.webhook_secret');

        $signature = $request->header('Stripe-Signature');

        $payload = $request->getContent();

        try {
            $event = Webhook::constructEvent($payload, $signature, $stripeWebhookSecret);

            Log::info('Stripe Webhook Event Received:', ['type' => $event->type]);

            return $this->handleStripeEvent($event);

        } catch (SignatureVerificationException $e) {
            Log::error('Stripe Webhook Signature Verification Failed:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);

        } catch (\UnexpectedValueException $e) {
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
                $paymentIntent = $event->data->object;
                Log::info('Payment Intent Succeeded:', ['id' => $paymentIntent->id]);
                $this->processPaymentIntentSucceeded($paymentIntent);
                break;

            case 'charge.succeeded':
                $charge = $event->data->object;
                Log::info('Charge Succeeded:', ['id' => $charge->id]);
                $this->processChargeSucceeded($charge);
                break;

       
            default:
                Log::warning('Unhandled Stripe Event:', ['type' => $event->type]);
                break;
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Process a successful payment intent.
     *
     * @param  object  $paymentIntent
     */
    private function processPaymentIntentSucceeded($paymentIntent)
    {
        Log::info('Processing PaymentIntent Succeeded:', ['id' => $paymentIntent->id]);
    }

    /**
     * Process a successful charge.
     *
     * @param  object  $charge
     */
    private function processChargeSucceeded($charge)
    {
        Log::info('Processing Charge Succeeded:', ['id' => $charge->id]);
    }
}
