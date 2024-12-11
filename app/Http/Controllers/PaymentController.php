<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Ramsey\Uuid\Uuid;
use App\Models\Payment;
use App\Models\OrderItem;
use App\Models\ProductOrder;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;

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
        $userId = Auth::id();
        $sessionId = session()->get('cart_session_id');
    
        $cartItems = ShoppingCart::when($userId, function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }, function ($query) use ($sessionId) {
            $query->where('session_id', $sessionId);
        })->get();
    
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'No items in the cart.');
        }
    
        $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->price);
    
        $paymentMethod = PaymentMethod::create([
            // 'name' => PaymentMethodEnum::CARD,
            'provider' => 'Stripe',
            'details' => 'Paid via Stripe Checkout',
        ]);
    
        $productOrder = ProductOrder::create([
            'user_id' => $userId, 
            'total_price' => $totalPrice,
            // 'status' => ProductOrderStatusEnum::COMPLETED,
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
            // 'status' => PaymentStatusEnum::SUCCESSFUL,
            'payment_transaction_id' => 'txn_' . Uuid::uuid4()->toString(),
            // 'payment_transaction_id' => uniqid('txn_'),
            'payment_details' => 'Payment processed successfully',
        ]);
    
        ShoppingCart::when($userId, function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }, function ($query) use ($sessionId) {
            $query->where('session_id', $sessionId);
        })->delete();
    
        return view('checkout.payment-success');
    }
    

    public function cancel()
    {
        return view('checkout.payment-cancel');
    }
}
