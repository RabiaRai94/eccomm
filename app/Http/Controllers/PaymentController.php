<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{

    public function checkout()
    {
        $cartItems = session('cart');
        $total = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('checkout.checkout', compact('cartItems', 'total'));
    }

    public function processPayment(Request $request)
    {
      
        Stripe::setApiKey(config('services.stripe.secret'));

       
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Cart Items', 
                    ],
                    'unit_amount' => $request->total * 100, 
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect($session->url);
    }

    public function success()
    {

        session()->forget('cart');

        return view('checkout.payment-success');
    }

    public function cancel()
    {
        return view('checkout.payment-cancel');
    }
}
