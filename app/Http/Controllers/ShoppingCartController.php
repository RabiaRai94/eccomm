<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
    // Display cart page
    // public function index(Request $request)
    // {
    //     $cartItems = session()->get('cart', []);
    //     $total = collect($cartItems)->sum(fn ($item) => $item['price'] * $item['quantity']);

    //     return view('landing.cart.index', compact('cartItems', 'total'));
    // }
    public function index(Request $request)
{
    $userId = auth()->id();
    
    if ($userId) {
        $cartItems = ShoppingCart::with('product')->where('user_id', $userId)->get();
    } else {
        $cartItems = collect(session()->get('cart', []))->map(function ($item, $key) {
            return (object) [
                'id' => $key, // Use the key as a unique identifier
                'product' => (object) [
                    'name' => $item['name'],
                    'image' => $item['image'],
                    'price' => $item['price'],
                ],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ];
        });
    }

    $total = $cartItems->sum(fn($item) => ($item->price ?? $item->product->price) * $item->quantity);

    return view('landing.cart.index', compact('cartItems', 'total'));
}

    
    // Add item to cart
    // public function addToCart(Request $request)
    // {
    //     $variant = ProductVariant::with('product')->findOrFail($request->variant_id);

    //     $cart = session()->get('cart', []);
    //     $key = "{$variant->product_id}_{$variant->id}";

    //     if (isset($cart[$key])) {
    //         $cart[$key]['quantity'] += $request->quantity ?? 1;
    //     } else {
    //         $cart[$key] = [
    //             'product_id' => $variant->product_id,
    //             'variant_id' => $variant->id,
    //             'name' => $variant->product->name,
    //             'image' => $variant->attachments->first()->file_path ?? 'default-image.jpg',
    //             'size' => $variant->size,
    //             'price' => $variant->price,
    //             'quantity' => $request->quantity ?? 1,
    //         ];
    //     }

    //     session()->put('cart', $cart);
    //     return response()->json(['message' => 'Product added to cart successfully!']);
    // }
    public function addToCart(Request $request)
    {
        $variant = ProductVariant::with('product')->findOrFail($request->variant_id);
        $userId = auth()->id();
    
        $productName = $variant->product ? $variant->product->name : 'Default Product';
        $image = $variant->attachments->first()->file_path ?? 'default-image.jpg'; // Default image
        $size = $variant->size;
        $price = $variant->price;
    
        if ($userId) {
            $cartItem= new ShoppingCart();

            $cartItem = ShoppingCart::where('user_id', $userId)
                                    ->where('product_id', $variant->product_id)
                                    ->where('variant_id', $variant->id)
                                    ->first();
    
            if ($cartItem) {
                $cartItem->quantity += $request->quantity ?? 1;
                $cartItem->save();
            } else {
                ShoppingCart::create([
                    'user_id' => $userId,
                    'product_id' => $variant->product_id,
                    'variant_id' => $variant->id,
                    'quantity' => $request->quantity ?? 1,
                    'price' => $price,
                ]);
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
                    'size' => $size,
                    'price' => $price,
                    'quantity' => $request->quantity ?? 1,
                ];
            }
    
            session()->put('cart', $cart);
        }
        return redirect()->route('shoping-cart')->with('success', 'Product added to cart successfully!');
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
        return redirect()->route('shoping-cart')->with('success', 'Cart updated successfully!');
    }

    public function removeFromCart($key)
    {
        $cart = session()->get('cart', []);
        unset($cart[$key]);
        session()->put('cart', $cart);
        return redirect()->route('shoping-cart')->with('success', 'Item removed from cart.');
    }
    
}
