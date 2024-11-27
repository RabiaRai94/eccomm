<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::find($request->product_id);
        $variant = $product->variants()->find($request->variant_id);
    
        $cart = session()->get('cart', []);
        $cart[] = [
            'name' => $product->name,
            'size' => $variant->size,
            'price' => $variant->price,
            'quantity' => 1,
            'image' => asset('storage/' . $variant->attachments->first()->file_path ?? 'default-image.jpg')
        ];
        session()->put('cart', $cart);
    
        return response()->json(['success' => true, 'cart' => $cart]);
    }
}
