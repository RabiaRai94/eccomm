<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
    public function addToCart(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
    
        $cartItem = ShoppingCart::where('user_id', auth()->id())
                                ->where('product_id', $productId)
                                ->first();
    
        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            ShoppingCart::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }
    
        $cartItems = ShoppingCart::with('product')
            ->where('user_id', auth()->id())
            ->get();
    
        // Calculate the total price
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    
        // Return the view with the cart data
        return view('cart.index', compact('cartItems', 'total'));
    }
    
    

}
