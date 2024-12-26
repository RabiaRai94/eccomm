<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Ramsey\Uuid\Uuid;
use PaymentMethodEnum;
use PaymentStatusEnum;
use App\Models\Payment;
use App\Models\OrderItem;
use ProductOrderStatusEnum;
use App\Models\ProductOrder;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\PaymentMethod;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe\Customer;
use Stripe\PaymentIntent;
class PaymentController extends Controller
{
    public function checkout()
    {
        $cartItems = session('cart');
        $total = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
        return view('checkout.checkout', compact('cartItems', 'total'));
    }
 
    
    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    
        $user = Auth::user();
        $email = $user ? $user->email : $request->input('email');
        $name = $user ? $user->name : $request->input('name');
    
        // Create a Stripe customer or fetch existing
        if ($user && $user->stripe_customer_id) {
            $customerId = $user->stripe_customer_id;
        } else {
            $customer = Customer::create([
                'email' => $email,
                'name' => $name,
            ]);
            $customerId = $customer->id;
    
            // Optional: Save customer ID to the user if logged in
            if ($user) {
                $user->stripe_customer_id = $customerId;
                $user->save();
            }
        }
    
        // Calculate total amount from cart
        $cartItems = session('cart', []);
        $total = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
    
        // Create a PaymentIntent
        $paymentIntent = PaymentIntent::create([
            'amount' => $total * 100, // Amount in cents
            'currency' => 'usd',
            'customer' => $customerId,
            'metadata' => ['integration_check' => 'elements_checkout'],
        ]);
    
        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
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
        $userId = Auth::id();
        $sessionId = session()->get('cart_session_id');
        $cartItems = ShoppingCart::when($userId, function ($query) use ($userId) {
            return $query->where('user_id', $userId);
        }, function ($query) use ($sessionId) {
            return $query->where('session_id', $sessionId);
        })->get();
    
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'No items in the cart.');
        }
    
        $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->price);
    
        // Save order, payment, and email logic
        // (Retain your existing order and email implementation here)
        $paymentMethod = PaymentMethod::create([
            'name' => PaymentMethodEnum::CARD,
            'provider' => 'Stripe',
            'details' => 'Paid via Stripe Checkout',
        ]);

        $productOrder = ProductOrder::create([
            'user_id' => $userId,
            'total_price' => $totalPrice,
            'status' => ProductOrderStatusEnum::COMPLETED,
            'payment_method_id' => $paymentMethod->id,
        ]);

        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $productOrder->id,
                'product_id' => $cartItem->product_id,
                'product_variant_id' => $cartItem->variant_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
            ]);
        }

        Payment::create([
            'order_id' => $productOrder->id,
            'amount' => $totalPrice,
            'status' => PaymentStatusEnum::SUCCESSFUL,
            'payment_transaction_id' => 'txn_' . Uuid::uuid4()->toString(),
            // 'payment_transaction_id' => uniqid('txn_'),
            'payment_details' => 'Payment processed successfully',
        ]);
        $productOrder->load('orderItems.product');


        Mail::to(Auth::user()->email)->send(new OrderConfirmationMail($productOrder));

        ShoppingCart::when($userId, function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }, function ($query) use ($sessionId) {
            $query->where('session_id', $sessionId);
        })->delete();
        session()->forget('cart'); 
        return view('checkout.payment-success');
    }
    
    // public function success()
    // {
    //     $userId = Auth::id();
    //     $sessionId = session()->get('cart_session_id');

    //     $cartItems = ShoppingCart::when($userId, function ($query) use ($userId) {
    //         $query->where('user_id', $userId);
    //     }, function ($query) use ($sessionId) {
    //         $query->where('session_id', $sessionId);
    //     })->get();

    //     // if ($cartItems->isEmpty()) {
    //     //     return redirect()->route('cart.index')->with('error', 'No items in the cart.');
    //     // }

    //     $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->price);

    //     $paymentMethod = PaymentMethod::create([
    //         'name' => PaymentMethodEnum::CARD,
    //         'provider' => 'Stripe',
    //         'details' => 'Paid via Stripe Checkout',
    //     ]);

    //     $productOrder = ProductOrder::create([
    //         'user_id' => $userId,
    //         'total_price' => $totalPrice,
    //         'status' => ProductOrderStatusEnum::COMPLETED,
    //         'payment_method_id' => $paymentMethod->id,
    //     ]);

    //     foreach ($cartItems as $cartItem) {
    //         OrderItem::create([
    //             'order_id' => $productOrder->id,
    //             'product_id' => $cartItem->product_id,
    //             'product_variant_id' => $cartItem->variant_id,
    //             'quantity' => $cartItem->quantity,
    //             'price' => $cartItem->price,
    //         ]);
    //     }

    //     Payment::create([
    //         'order_id' => $productOrder->id,
    //         'amount' => $totalPrice,
    //         'status' => PaymentStatusEnum::SUCCESSFUL,
    //         'payment_transaction_id' => 'txn_' . Uuid::uuid4()->toString(),
    //         // 'payment_transaction_id' => uniqid('txn_'),
    //         'payment_details' => 'Payment processed successfully',
    //     ]);

    //     // ShoppingCart::when($userId, function ($query) use ($userId) {
    //     //     $query->where('user_id', $userId);
    //     // }, function ($query) use ($sessionId) {
    //     //     $query->where('session_id', $sessionId);
    //     // })->delete();

    //     $productOrder->load('orderItems.product');


    //     Mail::to(Auth::user()->email)->send(new OrderConfirmationMail($productOrder));

    //     // Clear the shopping cart
    //     ShoppingCart::when($userId, function ($query) use ($userId) {
    //         $query->where('user_id', $userId);
    //     }, function ($query) use ($sessionId) {
    //         $query->where('session_id', $sessionId);
    //     })->delete();

    //     // Clear session cart (optional if you use a session-based cart as well)
    //     session()->forget('cart');
    //     return view('checkout.payment-success');
    // }


    public function cancel()
    {
        return view('checkout.payment-cancel');
    }
}
