<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use PaymentStatusEnum;
use App\Models\Payment;
use ProductOrderStatusEnum;
use App\Models\ProductOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
    private function processPendingPayments(Carbon $threshold)
    {
        $payments = Payment::where('status', PaymentStatusEnum::PENDING)
            ->where('created_at', '<=', $threshold)
            ->with('productOrder')
            ->get();

        Log::info("Found " . $payments->count() . " pending payments to process.");

        foreach ($payments as $payment) {
            try {
                DB::transaction(function () use ($payment) {
                    $payment->status = PaymentStatusEnum::SUCCESSFUL;
                    if ($payment->save()) {
                        Log::info("Payment ID {$payment->id} status updated to SUCCESSFUL.");
                    } else {
                        Log::error("Failed to update Payment ID {$payment->id}.");
                    }

                    // if ($payment->productOrder) {
                    //     $order = $payment->productOrder;
                    //     $order->status = ProductOrderStatusEnum::COMPLETED;
                    //     if ($order->save()) {
                    //         Log::info("ProductOrder ID {$order->id} status updated to COMPLETED.");
                    //     } else {
                    //         Log::error("Failed to update ProductOrder ID {$order->id}.");
                    //     }
                    // }
                });
            } catch (\Exception $e) {
                Log::error("Error processing Payment ID {$payment->id}: " . $e->getMessage());
            }
        }
    }



    private function processPendingOrders()
    {
        ProductOrder::where('status', ProductOrderStatusEnum::PENDING)
            ->chunk(100, function ($orders) {
                foreach ($orders as $order) {
                    try {
                        $order->status = ProductOrderStatusEnum::COMPLETED;
                        $order->save();

                        // $this->sendConfirmationEmail($order);
                    } catch (\Exception $e) {
                        Log::error("Error processing Order ID: {$order->id}. Exception: {$e->getMessage()}");
                    }
                }
            });
    }

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

    // private function sendConfirmationEmail(ProductOrder $order)
    // {
    //     try {
    //         $recipient = $order->user ? $order->user->email : $order->guest_email;
    //         if ($recipient) {
    //             Mail::to($recipient)->send(new OrderConfirmationMail($order));
    //             $this->info("Confirmation email sent for order ID {$order->id}");
    //         } else {
    //             Log::warning("No recipient email for order ID {$order->id}");
    //         }
    //     } catch (\Exception $e) {
    //         Log::error("Failed to send confirmation email for Order ID: {$order->id}. Exception: {$e->getMessage()}");
    //     }
    // }
    private function sendConfirmationEmail(ProductOrder $order)
    {
         try {
            $recipient = $order->user ? $order->user->email : $order->guest_email;
            if ($recipient) {
                Mail::to($recipient)->send(new OrderConfirmationMail($order));
                $this->info("Confirmation email sent for order ID {$order->id}");
            } else {
                Log::warning("No recipient email for order ID {$order->id}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to send confirmation email for Order ID: {$order->id}. Exception: {$e->getMessage()}");
        }
    }
}
