<?php

namespace App\Http\Controllers;

use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Calculation\Financial\CashFlow\Constant\Periodic\Payments;
use Ramsey\Uuid\Uuid;
use PaymentMethodEnum;
use PaymentStatusEnum;
use App\Models\Payment;
use App\Models\OrderItem;
use ProductOrderStatusEnum;
use App\Models\ProductOrder;
use App\Models\ShoppingCart;
use App\Models\PaymentMethod;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{

    public function checkout()
    {

        $userId = auth()->id();
        $sessionId = session()->get('cart_session_id');

        $cartItems = ShoppingCart::with(['product.variants'])
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            }, function ($query) use ($sessionId) {
                return $query->where('session_id', $sessionId);
            })->get();

        $total = $cartItems->sum(fn($item) => ($item->variant_price ?? $item->price) * $item->quantity);

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $total * 100,
            'currency' => 'usd',
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        return view('landing.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    public function createPaymentIntent(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

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


    // public function processPayment(Request $request)
    // {

    //     // \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    //     // $validatedData = $request->validate([

    //     //     'stripeToken' => 'required',
    //     // ]);

    //     // try {
    //     //     $paymentIntent = PaymentIntent::retrieve($validatedData['stripeToken']);

    //     //     if ($paymentIntent->status === 'succeeded') {
    //     //         Log::info('Payment succeeded, processing order');
    //     //         return $this->success($paymentIntent->id);
    //     //     } else {
    //     //         Log::info('Payment not successful, redirecting to cancel page');
    //     //         return redirect()->route('payment.cancel')->with('error', 'Payment requires further action.');
    //     //     }
    //     // } catch (\Exception $e) {
    //     //     Log::error('Payment failed: ' . $e->getMessage());
    //     //     return redirect()->route('payment.cancel')->with('error', 'Payment failed.');
    //     // }
    //     $paymentTransactionId = $request->input('paymentTransactionId');

    //     $userId = Auth::id();
    //     $sessionId = session()->get('cart_session_id');

    //     $cartItems = ShoppingCart::when($userId, function ($query) use ($userId) {
    //         $query->where('user_id', $userId);
    //     }, function ($query) use ($sessionId) {
    //         $query->where('session_id', $sessionId);
    //     })->get();

    //     if ($cartItems->isEmpty()) {
    //         return redirect()->route('cart.index')->with('error', 'No items in the cart.');
    //     } 
    //     else {

    //         // Save payment method
    //         $paymentMethod = PaymentMethod::create([
    //             'name' => PaymentMethodEnum::CARD,
    //             'provider' => 'Stripe',
    //             'details' => 'Paid via Stripe Checkout',
    //         ]);

    //         // Create product order
    //         $productOrder = ProductOrder::create([
    //             'user_id' => $userId,
    //             'total_price' => $totalPrice,
    //             'status' => ProductOrderStatusEnum::COMPLETED,
    //             'payment_method_id' => $paymentMethod->id,
    //         ]);

    //         // Save order items
    //         foreach ($cartItems as $cartItem) {
    //             OrderItem::create([
    //                 'order_id' => $productOrder->id,
    //                 'product_id' => $cartItem->product_id,
    //                 'product_variant_id' => $cartItem->variant_id,
    //                 'quantity' => $cartItem->quantity,
    //                 'price' => $cartItem->price,
    //             ]);
    //         }

    //         // Save payment details
    //         Payment::create([
    //             'order_id' => $productOrder->id,
    //             'amount' => $totalPrice,
    //             'status' => PaymentStatusEnum::SUCCESSFUL,
    //             'payment_transaction_id' => $paymentTransactionId,
    //             'payment_details' => 'Payment processed successfully',
    //         ]);

    //         // Send confirmation email
    //         $productOrder->load('orderItems.product');
    //         Mail::to(Auth::user()->email)->send(new OrderConfirmationMail($productOrder));

    //         // Clear shopping cart
    //         ShoppingCart::when($userId, function ($query) use ($userId) {
    //             $query->where('user_id', $userId);
    //         }, function ($query) use ($sessionId) {
    //             $query->where('session_id', $sessionId);
    //         })->delete();

    //         session()->forget('cart');

    //         return view('landing.payment.success', compact('productOrder'));
    //     } 
    // }
    public function processPayment(Request $request)
    {
        $paymentTransactionId = $request->input('paymentTransactionId');
    
        $userId = Auth::id();
        $sessionId = session()->get('cart_session_id');
    
        // Retrieve cart items
        $cartItems = ShoppingCart::when($userId, function ($query) use ($userId) {
            return $query->where('user_id', $userId);
        }, function ($query) use ($sessionId) {
            return $query->where('session_id', $sessionId);
        })->get();
    
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'No items in the cart.');
        }
    
        // Calculate total price
        $totalPrice = $cartItems->sum(fn($item) => ($item->variant_price ?? $item->price) * $item->quantity);
    
        try {
            // Save payment method
            $paymentMethod = PaymentMethod::create([
                'name' => PaymentMethodEnum::CARD,
                'provider' => 'Stripe',
                'details' => 'Paid via Stripe Checkout',
            ]);
    
            // Create product order
            $productOrder = ProductOrder::create([
                'user_id' => $userId,
                'total_price' => $totalPrice,
                'status' => ProductOrderStatusEnum::COMPLETED,
                'payment_method_id' => $paymentMethod->id,
            ]);
    
            // Save order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $productOrder->id,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->variant_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);
            }
    
            // Save payment details
            Payment::create([
                'order_id' => $productOrder->id,
                'amount' => $totalPrice,
                'status' => PaymentStatusEnum::SUCCESSFUL,
                'payment_transaction_id' => $paymentTransactionId,
                'payment_details' => 'Payment processed successfully',
            ]);
    
            // Send confirmation email
            $productOrder->load('orderItems.product');
            Mail::to(Auth::user()->email)->send(new OrderConfirmationMail($productOrder));
    
            // Clear shopping cart
            ShoppingCart::when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }, function ($query) use ($sessionId) {
                $query->where('session_id', $sessionId);
            })->delete();
    
            session()->forget('cart');
    
            return view('landing.payment.success', compact('productOrder'));
        } catch (\Exception $e) {
            Log::error('Order processing failed: ' . $e->getMessage());
            return redirect()->route('payment.cancel')->with('error', 'Failed to process the order.');
        }
    }
    


    // public function success($totalPrice, $paymentTransactionId)
    // {
    //     $userId = Auth::id();
    //     $sessionId = session()->get('cart_session_id');

    //     $cartItems = ShoppingCart::when($userId, function ($query) use ($userId) {
    //         $query->where('user_id', $userId);
    //     }, function ($query) use ($sessionId) {
    //         $query->where('session_id', $sessionId);
    //     })->get();

    //     if ($cartItems->isEmpty()) {
    //         return redirect()->route('cart.index')->with('error', 'No items in the cart.');
    //     }

    //     try {
    //         // Save payment method
    //         $paymentMethod = PaymentMethod::create([
    //             'name' => PaymentMethodEnum::CARD,
    //             'provider' => 'Stripe',
    //             'details' => 'Paid via Stripe Checkout',
    //         ]);

    //         // Create product order
    //         $productOrder = ProductOrder::create([
    //             'user_id' => $userId,
    //             'total_price' => $totalPrice,
    //             'status' => ProductOrderStatusEnum::COMPLETED,
    //             'payment_method_id' => $paymentMethod->id,
    //         ]);

    //         // Save order items
    //         foreach ($cartItems as $cartItem) {
    //             OrderItem::create([
    //                 'order_id' => $productOrder->id,
    //                 'product_id' => $cartItem->product_id,
    //                 'product_variant_id' => $cartItem->variant_id,
    //                 'quantity' => $cartItem->quantity,
    //                 'price' => $cartItem->price,
    //             ]);
    //         }

    //         // Save payment details
    //         Payment::create([
    //             'order_id' => $productOrder->id,
    //             'amount' => $totalPrice,
    //             'status' => PaymentStatusEnum::SUCCESSFUL,
    //             'payment_transaction_id' => $paymentTransactionId,
    //             'payment_details' => 'Payment processed successfully',
    //         ]);

    //         // Send confirmation email
    //         $productOrder->load('orderItems.product');
    //         Mail::to(Auth::user()->email)->send(new OrderConfirmationMail($productOrder));

    //         // Clear shopping cart
    //         ShoppingCart::when($userId, function ($query) use ($userId) {
    //             $query->where('user_id', $userId);
    //         }, function ($query) use ($sessionId) {
    //             $query->where('session_id', $sessionId);
    //         })->delete();

    //         session()->forget('cart');

    //         return view('landing.payment.success', compact('productOrder'));
    //     } catch (\Exception $e) {
    //         Log::error('Order processing failed: ' . $e->getMessage());
    //         return redirect()->route('payment.cancel')->with('error', 'Failed to process the order.');
    //     }
    // }




    public function cancel()
    {
        $error = session('error', 'Payment was canceled or failed.');
        return view('landing.payment.cancel', compact('error'));
    }
}
