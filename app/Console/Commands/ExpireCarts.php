<?php

namespace App\Console\Commands;

use App\Models\ShoppingCart;
use Illuminate\Console\Command;

class ExpireCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-carts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredCarts = ShoppingCart::where('expires_at', '<', now())->delete();

        $this->info($expiredCarts . ' expired carts have been removed.');
    }
}
