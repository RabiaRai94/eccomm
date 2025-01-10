<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Payment;
use ProductOrderStatusEnum;
use App\Models\ProductOrder;
use Illuminate\Console\Command;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PaymentStatusEnum;
class SendOrderConfirmationEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:send-confirmation-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send order confirmation emails for recently completed orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = Carbon::now()->subMinutes(1);

        $payments = Payment::where('status', PaymentStatusEnum::PENDING)
        ->where('created_at', '<=', $threshold)
            ->chunk(100,function($payments){
                foreach($payments as $payment){
                    //write your code .....
                }
            });

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
        return 0;
    }
}
