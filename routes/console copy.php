<?php

// use Illuminate\Foundation\Inspiring;
// use Illuminate\Support\Facades\Artisan;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');

use App\Models\ProductOrder;
use App\Models\ShoppingCart;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\PaymentController;
use Illuminate\Console\Scheduling\Schedule;

// Artisan::command('cart:expire', function () {
//     $threshold = Carbon::now()->subMinutes(1);

//     $expiredCarts = ShoppingCart::whereNull('user_id') 
//         ->where('updated_at', '<', $threshold)
//         ->get();

//     $count = $expiredCarts->count();

//     foreach ($expiredCarts as $cart) {
//         $cart->delete();
//     }

//     $this->info("{$count} expired shopping cart(s) have been removed.");
// })->describe('Expire shopping carts after 30 minutes of inactivity');

Artisan::command('cart:expire', function () {
    $threshold = Carbon::now()->subMinutes(1);

    $expiredCarts = ShoppingCart::where('updated_at', '<', $threshold)->get();

    $count = $expiredCarts->count();

    foreach ($expiredCarts as $cart) {
        $cart->delete();
    }

    $this->info("{$count} expired shopping cart(s) have been removed.");
})->describe('Expire shopping carts after 30 minutes of inactivity for all users.');



Artisan::command('order:send-confirmation-emails', function () {
    $threshold = Carbon::now()->subMinutes(10);

    $orders = ProductOrder::where('status', ProductOrderStatusEnum::COMPLETED)
        ->where('updated_at', '>=', $threshold)
        ->get();

    $count = 0;

    foreach ($orders as $order) {
        try {
            Mail::to($order->user->email)->send(new OrderConfirmationMail($order));
            $count++;
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email: ' . $e->getMessage());
        }
    }

    $this->info("{$count} order confirmation email(s) sent.");
})->describe('Send order confirmation emails for recently completed orders.');

Artisan::command('order:send-confirmation-emails', function () {
    $controller = app(PaymentController::class);
    $controller->sendOrderConfirmationEmails();
    $this->info('Order confirmation emails have been sent successfully.');
})->describe('Send order confirmation emails for completed orders.');

app(Schedule::class)->command('cart:expire')->everyMinute();
app(Schedule::class)->command('order:send-confirmation-emails')->everyTenMinutes();
