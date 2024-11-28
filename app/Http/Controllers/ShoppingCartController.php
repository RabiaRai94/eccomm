<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class ShoppingCartController extends Controller
{
   
    public function index(Request $request)
    {
        // $userId = auth()->id();
        if (Auth::check()) {
            $cartItems = ShoppingCart::with('product')->where('user_id', Auth::id())->get();
            $total = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
        } else {
            $cartItems = collect(session()->get('cart', []))->map(function ($item) {
                return (object) $item;
            });
            $total = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
        }
        // if ($userId) {
        //     $cartItems = ShoppingCart::with('product')->where('user_id', $userId)->get();
        // } else {
        //     $cartItems = collect(session()->get('cart', []))->map(function ($item, $key) {
        //         return (object) [
        //             'id' => $key,
        //             'product' => (object) [
        //                 'name' => $item['name'],
        //                 'image' => $item['image'],
        //                 'price' => $item['price'],
        //             ],
        //             'quantity' => $item['quantity'],
        //             'price' => $item['price'],
        //         ];
        //     });
        // }
    
        $total = $cartItems->sum(fn($item) => ($item->product->price ?? $item->price) * $item->quantity);
    
        return view('landing.cart.index', compact('cartItems', 'total'));
    }
    

    public function addToCart(Request $request)
    {
        $variant = ProductVariant::with('product')->findOrFail($request->variant_id);
        $userId = auth()->id();
        
        $productName = $variant->product ? $variant->product->name : 'Default Product';
        $image = $variant->attachments->first()->file_path ?? 'default-image.jpg';
        $price = $variant->price;
        
        if ($userId) {

            $existingCartItem = ShoppingCart::where([
                ['user_id', '=', $userId],
                ['product_id', '=', $variant->product_id],
                ['variant_id', '=', $variant->id]
            ])->first();
    
            if ($existingCartItem) {

                $existingCartItem->quantity += $request->quantity ?? 1;
                $existingCartItem->price = $price;
                $existingCartItem->save();
            } else {
             
                $cartItem = new ShoppingCart();
                $cartItem->user_id = $userId;
                $cartItem->product_id = $variant->product_id;
                $cartItem->variant_id = $variant->id;
                $cartItem->quantity = $request->quantity ?? 1;
                $cartItem->price = $price;
    
                if ($cartItem->save()) {
                    \Log::info('Cart item saved successfully:', $cartItem->toArray());
                } else {
                    \Log::error('Failed to save cart item.');
                    return response()->json(['message' => 'Failed to add product to cart.'], 500);
                }
                
            }
        } else {
           
            $cart = session()->get('cart', []);
            $key = "{$variant->product_id}_{$variant->id}";
    
            if (isset($cart[$key])) {
                $cart[$key]['quantity'] += $request->quantity ?? 1;
            } else {
                $cart[$key] = [
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->id,
                    'name' => $productName,
                    'image' => $image,
                    'price' => $price,
                    'quantity' => $request->quantity ?? 1,
                ];
            }
    
            session()->put('cart', $cart);
        }
        
        return response()->json(['message' => 'Product added to cart successfully!']);
    }
    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);

        foreach ($request->quantity as $key => $quantity) {
            if (isset($cart[$key])) {
                $cart[$key]['quantity'] = $quantity;
            }
        }

        session()->put('cart', $cart);
        return redirect()->route('shopping-cart')->with('success', 'Cart updated successfully!');
    }

    public function removeFromCart($key)
    {
        if (auth()->check()) {
            ShoppingCart::where('id', $key)->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$key]);
            session()->put('cart', $cart);
        }
    
        return redirect()->route('shopping-cart')->with('success', 'Item removed from cart.');
    }
    
    
}
