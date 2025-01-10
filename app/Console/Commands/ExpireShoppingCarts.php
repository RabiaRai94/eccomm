<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\ShoppingCart;
use Illuminate\Console\Command;

class ExpireShoppingCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire shopping carts after 30 minutes of inactivity';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = Carbon::now()->subMinutes(1);

        $expiredCarts = ShoppingCart::whereNull('user_id') 
            ->where('updated_at', '<', $threshold)
            ->get();

        $count = $expiredCarts->count();

        foreach ($expiredCarts as $cart) {
            $cart->delete();
        }

        $this->info("{$count} expired shopping cart(s) have been removed.");
        
        return 0;
    }
}
