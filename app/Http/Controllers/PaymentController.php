<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use PaymentMethodEnum;
use PaymentStatusEnum;
use App\Models\Payment;
use App\Models\Customer;
use Stripe\StripeClient;
use App\Models\OrderItem;
use Stripe\PaymentIntent;
use ProductOrderStatusEnum;
use App\Models\ProductOrder;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Calculation\Financial\CashFlow\Constant\Periodic\Payments;

class PaymentController extends Controller
{
    // public function checkout(Request $request)
    // {
    //     $userId = auth()->id();
    //     $sessionId = session()->get('cart_session_id');


    //     $cartItems = ShoppingCart::with(['product.variants'])
    //         ->when($userId, function ($query) use ($userId) {
    //             return $query->where('user_id', $userId);
    //         }, function ($query) use ($sessionId) {
    //             return $query->where('session_id', $sessionId);
    //         })->get();


    //     $total = $cartItems->sum(fn($item) => ($item->variant_price ?? $item->price) * $item->quantity);

    //     $user = auth()->user();
    //     $name = $user ? $user->name : null;
    //     $email = $user ? $user->email : null;

    //     if (!$user && (!$request->has('name') || !$request->has('email'))) {
    //         return view('landing.guest_checkout', [
    //             'cartItems' => $cartItems,
    //             'total' => $total,
    //         ]);
    //     }

    //     if (!$user) {
    //         $request->validate([
    //             'name' => 'required|string|max:255',
    //             'email' => 'required|email|max:255',
    //         ]);

    //         $name = $request->input('name');
    //         $email = $request->input('email');
    //     }

    //     \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    //     $stripe = new StripeClient(config('services.stripe.secret'));
    //     $stripeCustomer = $stripe->customers->create([
    //         'name' => $name,
    //         'email' => $email,
    //     ]);

    //     $customer = Customer::create([
    //         'name' => $name,
    //         'email' => $email,
    //         'stripe_customer_id' => $stripeCustomer->id,
    //         'auth_user_id' => $user ? $user->id : null,
    //     ]);

    //     $paymentIntent = PaymentIntent::create([
    //         'amount' => $total * 100,
    //         'currency' => 'usd',
    //         'automatic_payment_methods' => ['enabled' => true],
    //         'customer' => $stripeCustomer->id,
    //     ]);

    //     return view('landing.checkout', [
    //         'cartItems' => $cartItems,
    //         'total' => $total,
    //         'clientSecret' => $paymentIntent->client_secret,
    //     ]);
    // }
    public function checkout(Request $request)
    {
        // Check if customer is authenticated
        $customer = Auth::guard('customer')->user();
    
        // Handle unauthenticated customers
        if (!$customer) {
            // Redirect to login/register if customer is not authenticated
            return redirect()->route('client.login')->with('error', 'Please login or register to proceed to checkout.');
        }
    
        // If customer is authenticated, proceed with checkout
        $sessionId = session()->get('cart_session_id');
        $cartItems = ShoppingCart::with(['product.variants'])
            ->when($customer, function ($query) use ($customer) {
                return $query->where('user_id', $customer->id);
            }, function ($query) use ($sessionId) {
                return $query->where('session_id', $sessionId);
            })->get();
    
        $total = $cartItems->sum(fn($item) => ($item->variant_price ?? $item->price) * $item->quantity);
    
        // Create a Stripe customer if not already created
        if (!$customer->stripe_customer_id) {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $stripeCustomer = $stripe->customers->create([
                'name' => $customer->name,
                'email' => $customer->email,
            ]);
    
            $customer->update(['stripe_customer_id' => $stripeCustomer->id]);
        }
    
        // Create a PaymentIntent for the authenticated customer
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $total * 100,
            'currency' => 'usd',
            'automatic_payment_methods' => ['enabled' => true],
            'customer' => $customer->stripe_customer_id,
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
            $user = Auth::user();
            $name = $user ? $user->name : $request->input('name', 'Guest');
            $email = $user ? $user->email : $request->input('email');

            $stripeCustomer = $stripe->customers->create([
                'name' => $name,
                'email' => $email,
            ]);

            $customer = Customer::create([
                'name' => $name,
                'email' => $email,
                'stripe_customer_id' => $stripeCustomer->id,  
                'auth_user_id' => $user ? $user->id : null, 
            ]);

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => 5000, 
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
                'customer' => $stripeCustomer->id,  
            ]);

            return response()->json(['clientSecret' => $paymentIntent->client_secret]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function processPayment(Request $request)
    {
        $paymentTransactionId = $request->input('paymentTransactionId');
        $userId = Auth::id();
        $sessionId = session()->get('cart_session_id');
        $guestEmail = $request->input('guest_email'); // For guest users
    
        $cartItems = ShoppingCart::when($userId, function ($query) use ($userId) {
            return $query->where('user_id', $userId);
        }, function ($query) use ($sessionId) {
            return $query->where('session_id', $sessionId);
        })->get();
    
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'No items in the cart.');
        }
    
        $totalPrice = $cartItems->sum(fn($item) => ($item->variant_price ?? $item->price) * $item->quantity);
    
        try {
            $paymentMethod = PaymentMethod::create([
                'name' => PaymentMethodEnum::CARD,
                'provider' => 'Stripe',
                'details' => 'Paid via Stripe Checkout',
            ]);
    
            $productOrder = ProductOrder::create([
                'user_id' => $userId,
                'guest_email' => $guestEmail,
                'total_price' => $totalPrice,
                'status' => ProductOrderStatusEnum::PENDING,
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
                'status' => PaymentStatusEnum::PENDING,
                'payment_transaction_id' => $paymentTransactionId,
                'payment_details' => 'Payment processed successfully',
            ]);
    
            $productOrder->load('orderItems.product');
            // if ($guestEmail || ($user = Auth::user())) {
            //     $recipient = $guestEmail ?? $user->email;
            //     Mail::to($recipient)->send(new OrderConfirmationMail($productOrder));
            // }
    
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
    
  


    public function cancel()
    {
        $error = session('error', 'Payment was canceled or failed.');
        return view('landing.payment.cancel', compact('error'));
    }
}
