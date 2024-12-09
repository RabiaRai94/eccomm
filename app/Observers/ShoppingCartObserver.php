<?php

namespace App\Observers;

use App\Models\ShoppingCart;
use App\Models\ProductVariant;

class ShoppingCartObserver
{
    public function creating(ShoppingCart $cartItem)
    {
        $variant = ProductVariant::find($cartItem->variant_id);
    
        if (!$variant || $variant->stock < $cartItem->quantity) {
            abort(400, 'Insufficient stock available.');
        }
    
        $variant->decrement('stock', $cartItem->quantity);
    }
    
    public function updating(ShoppingCart $cartItem)
    {
        $variant = ProductVariant::find($cartItem->variant_id);
        $originalQuantity = $cartItem->getOriginal('quantity');
        $newQuantity = $cartItem->quantity;
    
        if ($variant->stock + $originalQuantity < $newQuantity) {
            abort(400, 'Insufficient stock available.');
        }
    
        $variant->stock += $originalQuantity;
        $variant->stock -= $newQuantity;
        $variant->save();
    }
    

    public function deleting(ShoppingCart $cartItem)
    {
        $variant = ProductVariant::find($cartItem->variant_id);
        $variant->increment('stock', $cartItem->quantity);
    }
}
