<?php

namespace App\Http\Controllers;

use Stripe\Charge;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function checkout()
    {
        return view('landing.checkout');
    }

    // public function createPaymentIntent(Request $request)
    // {
    //     Stripe::setApiKey(env('STRIPE_SECRET'));

    //     $intent = PaymentIntent::create([
    //         'amount' => $request->total * 100,
    //         'currency' => 'usd',
    //         'payment_method_types' => ['card'],
    //     ]);

    //     return response()->json(['clientSecret' => $intent->client_secret]);
    // }
    // public function createPaymentIntent(Request $request)
    // {
    //     // Set your Stripe secret key
    //     Stripe::setApiKey(env('STRIPE_SECRET'));

    //     try {
    //         // Create a PaymentIntent
    //         $paymentIntent = PaymentIntent::create([
    //             'amount' => 5000, // Amount in cents (e.g., $50.00)
    //             'currency' => 'usd',
    //             'automatic_payment_methods' => ['enabled' => true],
    //         ]);

    //         return response()->json(['clientSecret' => $paymentIntent->client_secret]);

    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $stripe = new \Stripe\StripeClient('sk_test_51QSENdCOqW9AveYvymlk3vLPlPnrcV9jJJMNbkJK9p8jwnQELm3d4uAMtUTp55erc41JfDrmtVU1MobV7hNqsKy600lPJUbQEI');
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => 5000,
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            return response()->json(['clientSecret' => $paymentIntent->client_secret]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function processPayment(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    
        try {
            Log::info('Processing payment', $request->all());
    
            $charge = Charge::create([
                'amount' => $request->price * 100, 
                'currency' => 'usd',
                'source' => $request->stripeToken, 
                'description' => 'Payment for Order',
            ]);
    
            Log::info('Charge successful', $charge->toArray());
    
            return redirect()->route('payment.success')->with('message', 'Payment successful!');
        } catch (\Exception $e) {
          
            Log::error('Payment failed: ' . $e->getMessage());
    
            return redirect()->route('payment.cancel')->with('error', $e->getMessage());
        }
    }
    

    public function success()
    {
        return view('landing.payment.success');
    }

    public function cancel()
    {
        return view('landing.payment.cancel');
    }
}
