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
    protected $description = 'Send order confirmation emails for recently completed orders and update pending statuses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = Carbon::now()->subMinutes(1);

        $this->processPendingPayments($threshold);
    
        $this->processPendingOrders();
    
        $this->processCompletedOrders($threshold);
    
        return 0;
    }

    /**
     * Process pending payments and update their statuses.
     *
     * @param \Carbon\Carbon $threshold
     */
    private function processPendingPayments(Carbon $threshold)
    {
        Payment::where('status', PaymentStatusEnum::PENDING)
            ->where('created_at', '<=', $threshold)
            ->chunk(100, function ($payments) {
                foreach ($payments as $payment) {
                    try {
                        // Update Payment status
                        $payment->status = PaymentStatusEnum::SUCCESSFUL;
                        $payment->save();

                        // Fetch the associated ProductOrder
                        $order = $payment->productOrder;

                        // Update ProductOrder status if it's in PENDING
                        if ($order && $order->status === ProductOrderStatusEnum::PENDING) {
                            Log::info("Processing ProductOrder ID: {$order->id}");
                            $order->status = ProductOrderStatusEnum::COMPLETED;
                            $order->save();

                            // Optionally send a confirmation email
                            $this->sendConfirmationEmail($order);
                        } else {
                            Log::warning("ProductOrder not found or not in PENDING status for Payment ID: {$payment->id}");
                        }
                    } catch (\Exception $e) {
                        Log::error("Error processing Payment ID: {$payment->id}. Exception: {$e->getMessage()}");
                    }
                }
            });
    }
   
    private function processPendingOrders()
    {
        ProductOrder::where('status', ProductOrderStatusEnum::PENDING)
            ->chunk(100, function ($orders) {
                foreach ($orders as $order) {
                    try {
                        Log::info("Processing ProductOrder ID: {$order->id}");

                       
                        $order->status = ProductOrderStatusEnum::COMPLETED;
                        $order->save();

                    
                        $this->sendConfirmationEmail($order);

                        Log::info("ProductOrder ID: {$order->id} marked as COMPLETED.");
                    } catch (\Exception $e) {
                        Log::error("Error processing ProductOrder ID: {$order->id}. Exception: {$e->getMessage()}");
                    }
                }
            });
    }



    /**
     * Process orders marked as completed within the last 10 minutes.
     *
     * @param \Carbon\Carbon $threshold
     */
    private function processCompletedOrders(Carbon $threshold)
    {
        ProductOrder::where('status', ProductOrderStatusEnum::COMPLETED)
            ->where('updated_at', '>=', $threshold)
            ->chunk(100, function ($orders) {
                foreach ($orders as $order) {
                    $this->sendConfirmationEmail($order);
                }
            });
    }

    /**
     * Mark an order as completed and send a confirmation email.
     *
     * @param \App\Models\ProductOrder $order
     */
    private function markOrderCompleted(ProductOrder $order)
    {
        try {
            Log::info("Updating order ID {$order->id} to COMPLETED.");
            $order->status = ProductOrderStatusEnum::COMPLETED;
            $order->save();

            $this->sendConfirmationEmail($order);
        } catch (\Exception $e) {
            Log::error("Error updating order ID {$order->id}: {$e->getMessage()}");
        }
    }



    /**
     * Send a confirmation email for the given order.
     *
     * @param \App\Models\ProductOrder $order
     */
    private function sendConfirmationEmail(ProductOrder $order)
    {
        try {
            Mail::to($order->user->email)->send(new OrderConfirmationMail($order));
            $this->info("Confirmation email sent for order ID {$order->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send confirmation email for order ID {$order->id}: {$e->getMessage()}");
        }
    }
}
