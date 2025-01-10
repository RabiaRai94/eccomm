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
    public function checkout(Request $request)
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

        $user = auth()->user();
        $name = $user ? $user->name : null;
        $email = $user ? $user->email : null;

        if (!$user && (!$request->has('name') || !$request->has('email'))) {
            return view('landing.guest_checkout', [
                'cartItems' => $cartItems,
                'total' => $total,
            ]);
        }

        if (!$user) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);

            $name = $request->input('name');
            $email = $request->input('email');
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $stripe = new StripeClient(config('services.stripe.secret'));
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

        $paymentIntent = PaymentIntent::create([
            'amount' => $total * 100,
            'currency' => 'usd',
            'automatic_payment_methods' => ['enabled' => true],
            'customer' => $stripeCustomer->id,
        ]);

        return view('landing.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    // public function checkout()
    // {
    //     $userId = auth()->id();
    //     $sessionId = session()->get('cart_session_id');

    //     // Retrieve cart items
    //     $cartItems = ShoppingCart::with(['product.variants'])
    //         ->when($userId, function ($query) use ($userId) {
    //             return $query->where('user_id', $userId);
    //         }, function ($query) use ($sessionId) {
    //             return $query->where('session_id', $sessionId);
    //         })->get();

    //     // Calculate total
    //     $total = $cartItems->sum(fn($item) => ($item->variant_price ?? $item->price) * $item->quantity);

    //     // If user is not authenticated, show the guest checkout form
    //     if (!auth()->check()) {
    //         return view('landing.guest_checkout', [
    //             'cartItems' => $cartItems,
    //             'total' => $total,
    //         ]);
    //     }

    //     // Set the Stripe secret key
    //     \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    //     // Retrieve user information
    //     $user = auth()->user();
    //     $name = $user->name;
    //     $email = $user->email;

    //     // Create a Stripe customer
    //     $stripe = new StripeClient(config('services.stripe.secret'));
    //     $stripeCustomer = $stripe->customers->create([
    //         'name' => $name,
    //         'email' => $email,
    //     ]);

    //     // Save the customer to the database
    //     $customer = Customer::create([
    //         'name' => $name,
    //         'email' => $email,
    //         'stripe_customer_id' => $stripeCustomer->id,
    //         'auth_user_id' => $user->id,
    //     ]);

    //     // Create a PaymentIntent for the checkout
    //     $paymentIntent = PaymentIntent::create([
    //         'amount' => $total * 100, // Convert amount to cents
    //         'currency' => 'usd',
    //         'automatic_payment_methods' => ['enabled' => true],
    //         'customer' => $stripeCustomer->id, // Associate the PaymentIntent with the Stripe customer
    //     ]);

    //     // Pass data to the checkout Blade view
    //     return view('landing.checkout', [
    //         'cartItems' => $cartItems,
    //         'total' => $total,
    //         'clientSecret' => $paymentIntent->client_secret,
    //     ]);
    // }
    // public function processGuestCheckout(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|max:255',
    //     ]);

    //     $name = $request->input('name');
    //     $email = $request->input('email');

    //     $sessionId = session()->get('cart_session_id');

    //     $cartItems = ShoppingCart::with(['product.variants'])
    //         ->where('session_id', $sessionId)
    //         ->get();

    //     $total = $cartItems->sum(fn($item) => ($item->variant_price ?? $item->price) * $item->quantity);

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
    //         'auth_user_id' => null, 
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

    // public function checkout()
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

    //     \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    //     $paymentIntent = PaymentIntent::create([
    //         'amount' => $total * 100,
    //         'currency' => 'usd',
    //         'automatic_payment_methods' => ['enabled' => true],
    //     ]);

    //     return view('landing.checkout', [
    //         'cartItems' => $cartItems,
    //         'total' => $total,
    //         'clientSecret' => $paymentIntent->client_secret,
    //     ]);
    // }

    public function createPaymentIntent(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $stripe = new \Stripe\StripeClient('sk_test_51QSENdCOqW9AveYvymlk3vLPlPnrcV9jJJMNbkJK9p8jwnQELm3d4uAMtUTp55erc41JfDrmtVU1MobV7hNqsKy600lPJUbQEI');
        try {
            // Step 1: Get the user info (authenticated or from request)
            $user = Auth::user();
            $name = $user ? $user->name : $request->input('name', 'Guest');
            $email = $user ? $user->email : $request->input('email');

            // Step 2: Create the customer on Stripe
            $stripeCustomer = $stripe->customers->create([
                'name' => $name,
                'email' => $email,
            ]);

            // Step 3: Store the customer in your local database
            $customer = Customer::create([
                'name' => $name,
                'email' => $email,
                'stripe_customer_id' => $stripeCustomer->id,  // Store Stripe customer ID
                'auth_user_id' => $user ? $user->id : null,  // Link to authenticated user (nullable for guest)
            ]);

            // Step 4: Create a PaymentIntent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => 5000,  // Amount in cents
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
                'customer' => $stripeCustomer->id,  // Associate the PaymentIntent with the Stripe customer
            ]);

            // Return the client secret for the PaymentIntent
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
    public function sendOrderConfirmationEmails()
    {
        // Define the threshold for recently completed orders
        $threshold = Carbon::now()->subMinutes(10);

        // Fetch completed orders that haven't had a confirmation email sent
        $orders = ProductOrder::where('status', ProductOrderStatusEnum::COMPLETED)
            ->where('updated_at', '>=', $threshold)
            ->get();

        $count = 0;

        foreach ($orders as $order) {
            try {
                // Send confirmation email
                Mail::to($order->user->email)->send(new OrderConfirmationMail($order));
                $count++;
            } catch (\Exception $e) {
                Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            }
        }

        return response()->json([
            'message' => "{$count} order confirmation email(s) sent.",
        ]);
    }
    public function processPayment(Request $request)
    {
        $paymentTransactionId = $request->input('paymentTransactionId');

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

        $totalPrice = $cartItems->sum(fn($item) => ($item->variant_price ?? $item->price) * $item->quantity);

        try {
            $paymentMethod = PaymentMethod::create([
                'name' => PaymentMethodEnum::CARD,
                'provider' => 'Stripe',
                'details' => 'Paid via Stripe Checkout',
            ]);
            // Create product order
            $productOrder = ProductOrder::create([
                'user_id' => $userId,
                'total_price' => $totalPrice,
                'status' => ProductOrderStatusEnum::PENDING,
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
                'status' => PaymentStatusEnum::PENDING,
                'payment_transaction_id' => $paymentTransactionId,
                'payment_details' => 'Payment processed successfully',
            ]);

            $productOrder->load('orderItems.product');
            // Mail::to(Auth::user()->email)->send(new OrderConfirmationMail($productOrder));
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
