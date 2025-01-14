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



app(Schedule::class)->command('cart:expire');
// app(Schedule::class)->command('order:send-confirmation-emails');

app(Schedule::class)->command('order:send-confirmation-emails');
