<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class ShoppingCartController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $sessionId = session()->get('cart_session_id');
    
        if ($userId) {
            $cartItems = ShoppingCart::with('product')->where('user_id', $userId)->get();
        } else {
            $cartItems = ShoppingCart::with('product')->where('session_id', $sessionId)->get();
        }
    
        // Add max_stock for each item
        foreach ($cartItems as $item) {
            $item->max_stock = $item->product->stock; // Pass the stock level to the view
        }
    
        $total = $cartItems->sum(fn($item) => ($item->product->price ?? $item->price) * $item->quantity);
    
        return view('landing.cart.index', compact('cartItems', 'total'));
    }
    

    public function addToCart(Request $request)
    {
        $variant = ProductVariant::with('product')->findOrFail($request->variant_id);
        $userId = auth()->id();

        $sessionId = session()->get('cart_session_id') ?? Str::uuid()->toString();
        session()->put('cart_session_id', $sessionId);

        $productName = $variant->product ? $variant->product->name : 'Default Product';
        $image = $variant->attachments->first()->file_path ?? 'default-image.jpg';
        $price = $variant->price;

        if ($userId) {
            $existingCartItem = ShoppingCart::where([
                ['user_id', '=', $userId],
                ['product_id', '=', $variant->product_id],
                ['variant_id', '=', $variant->id]
            ])->first();
        } else {
            $existingCartItem = ShoppingCart::where([
                ['session_id', '=', $sessionId],
                ['product_id', '=', $variant->product_id],
                ['variant_id', '=', $variant->id]
            ])->first();
        }

        if ($existingCartItem) {
            $existingCartItem->quantity += $request->quantity ?? 1;
            $existingCartItem->price = $price;
            $existingCartItem->save();
        } else {
            $cartItem = new ShoppingCart();
            $cartItem->user_id = $userId;
            $cartItem->session_id = $userId ? null : $sessionId;
            $cartItem->product_id = $variant->product_id;
            $cartItem->variant_id = $variant->id;
            $cartItem->quantity = $request->quantity ?? 1;
            $cartItem->price = $price;
            $cartItem->save();
        }

        return response()->json(['message' => 'Product added to cart successfully!']);
    }

    public function cartShow()
    {
        $userId = auth()->id();
        $sessionId = session()->get('cart_session_id');
    
        $cartItems = ShoppingCart::with('product')->when($userId, function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }, function ($query) use ($sessionId) {
            $query->where('session_id', $sessionId);
        })->get();
    
        // Add max_stock for each item
        foreach ($cartItems as $item) {
            $item->max_stock = $item->product->stock; // Pass the stock level to the view
        }
    
        $total = $cartItems->sum(fn($item) => ($item->product->price ?? $item->price) * $item->quantity);
    
        return view('landing.cart.index', compact('cartItems', 'total'));
    }
    
    public function updateCart(Request $request)
    {
        foreach ($request->quantity as $id => $quantity) {
            $cartItem = ShoppingCart::find($id);
            $variant = $cartItem ? ProductVariant::find($cartItem->variant_id) : null;

            if ($variant && $quantity > $variant->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot update quantity for {$variant->product->name}. Only {$variant->stock} left in stock."
                ]);
            }

            if ($cartItem && $quantity > 0) {
                $cartItem->quantity = $quantity;
                $cartItem->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Cart updated successfully!']);
    }


    public function removeFromCart($id)
    {
        $cartItem = ShoppingCart::find($id);

        if ($cartItem) {
            $cartItem->delete();
        }

        return response()->json([
            'message' => 'Item removed successfully!',

        ]);
    }
}