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
    // public function index(Request $request)
    // {
    //     $userId = auth()->id();
    //     $sessionId = session()->get('cart_session_id');
    //     if ($userId) {
    //         $cartItems = ShoppingCart::with('product')->where('user_id', $userId)->get();
    //     } else {
    //         $cartItems = ShoppingCart::with('product')->where('session_id', $sessionId)->get();
    //     }
    //     $cartItems = ShoppingCart::with(['product.variants'])->get();
    //    dd($cartItems);
    
    //     $total = $cartItems->sum(fn($item) => ($item->product->price ?? $item->price) * $item->quantity);
    //     return view('landing.cart.index', compact('cartItems', 'total'));
    // }
    public function index(Request $request)
    {
        $userId = auth()->id();
        $sessionId = session()->get('cart_session_id');
        if ($userId) {
            $cartItems = ShoppingCart::with(['product.variants'])->where('user_id', $userId)->get();
        } else {
            $cartItems = ShoppingCart::with(['product.variants'])->where('session_id', $sessionId)->get();
        }
        $cartItems->each(function ($item) {
            if ($item->product && $item->product->variants->isNotEmpty()) {
                $variant = $item->product->variants->first(); 
                $item->variant_stock = $variant->stock;
            }
        });
        
  
        
        $total = $cartItems->sum(fn($item) => ($item->variant_price ?? $item->price) * $item->quantity);
    
        return view('landing.cart.index', compact('cartItems', 'total'));
    }
    
    // public function addToCart(Request $request)
    // {
    //     $variant = ProductVariant::with('product')->findOrFail($request->variant_id);

    //     if (Auth::check()) {
    //         $userId = auth()->id();
    //         $sessionId = session()->get('cart_session_id');
    //         if ($sessionId) {
    //             ShoppingCart::where('session_id', $sessionId)->update(['user_id' => $userId, 'session_id' => null]);
    //             session()->forget('cart_session_id');
    //         }
    //     }
    //     // $sessionId = session()->get('cart_session_id') ?? Str::uuid()->toString();
    //     // session()->put('cart_session_id', $sessionId);
    //     // session()->put('cart_expires_at', now()->addMinutes(.5));
    //     $productName = $variant->product ? $variant->product->name : 'Default Product';
    //     $image = $variant->attachments->first()->file_path ?? 'default-image.jpg';
    //     $price = $variant->price;

    //     if ($userId) {
    //         $existingCartItem = ShoppingCart::where([
    //             ['user_id', '=', $userId],
    //             ['product_id', '=', $variant->product_id],
    //             ['variant_id', '=', $variant->id]
    //         ])->first();
    //     } else {
    //         $existingCartItem = ShoppingCart::where([
    //             ['session_id', '=', $sessionId],
    //             ['product_id', '=', $variant->product_id],
    //             ['variant_id', '=', $variant->id]
    //         ])->first();
    //     }

    //     if ($existingCartItem) {
    //         $existingCartItem->quantity += $request->quantity ?? 1;
    //         $existingCartItem->price = $price;
    //         $existingCartItem->save();
    //     } else {
    //         $cartItem = new ShoppingCart();
    //         $cartItem->user_id = $userId;
    //         $cartItem->session_id = $userId ? null : $sessionId;
    //         $cartItem->product_id = $variant->product_id;
    //         $cartItem->variant_id = $variant->id;
    //         $cartItem->quantity = $request->quantity ?? 1;
    //         $cartItem->price = $price;
    //         $cartItem->save();
    //     }
    //     $cartCount = ShoppingCart::when($userId, function ($query) use ($userId) {
    //         $query->where('user_id', $userId);
    //     }, function ($query) use ($sessionId) {
    //         $query->where('session_id', $sessionId);
    //     })->count();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Product added to cart successfully!',
    //         'cartCount' => $cartCount,
    //     ]);
    //     // return response()->json(['message' => 'Product added to cart successfully!']);
    // }
    public function addToCart(Request $request)
    {
        $variant = ProductVariant::with('product')->findOrFail($request->variant_id);
        $userId = Auth::check() ? auth()->id() : null; 
        $sessionId = session()->get('cart_session_id') ?? Str::uuid()->toString(); 
        session()->put('cart_session_id', $sessionId); 
        $productName = $variant->product ? $variant->product->name : 'Default Product';
        $image = $variant->attachments->first()->file_path ?? 'default-image.jpg';
        $price = $variant->price;

        $existingCartItem = ShoppingCart::when($userId, function ($query) use ($userId, $variant) {
            $query->where([
                ['user_id', '=', $userId],
                ['product_id', '=', $variant->product_id],
                ['variant_id', '=', $variant->id],
            ]);
        }, function ($query) use ($sessionId, $variant) {
            $query->where([
                ['session_id', '=', $sessionId],
                ['product_id', '=', $variant->product_id],
                ['variant_id', '=', $variant->id],
            ]);
        })->first();

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

        $cartCount = ShoppingCart::when($userId, function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }, function ($query) use ($sessionId) {
            $query->where('session_id', $sessionId);
        })->count();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cartCount' => $cartCount,
        ]);
    }

    public function cartCount()
    {
        $userId = auth()->id();
        $sessionId = session()->get('cart_session_id');
        $cartCount = ShoppingCart::when($userId, function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }, function ($query) use ($sessionId) {
            $query->where('session_id', $sessionId);
        })->count();

        return response()->json(['cartCount' => $cartCount]);
    }

    public function cartShow()
    {
        $userId = auth()->id();
        $sessionId = session()->get('cart_session_id');
        $cartItems = ShoppingCart::with(['product.variants'])->get();
        $cartItems = ShoppingCart::with('product')->when($userId, function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }, function ($query) use ($sessionId) {
            $query->where('session_id', $sessionId);
        })->get();

        foreach ($cartItems as $item) {
            $item->max_stock = $item->product->stock;
        }

        $total = $cartItems->sum(fn($item) => ($item->product->price ?? $item->price) * $item->quantity);
        return view('landing.cart.index', compact('cartItems', 'total'));
    }

    // public function updateCart(Request $request)
    // {
    //     foreach ($request->quantity as $id => $quantity) {
    //         $cartItem = ShoppingCart::find($id);
    //         $variant = $cartItem ? ProductVariant::find($cartItem->variant_id) : null;

    //         if ($variant && $quantity > $variant->stock) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => "Cannot update quantity for {$variant->product->name}. Only {$variant->stock} left in stock."
    //             ]);
    //         }

    //         if ($cartItem && $quantity > 0) {
    //             $cartItem->quantity = $quantity;
    //             $cartItem->save();
    //         }
    //     }
    //     return response()->json(['success' => true, 'message' => 'Cart updated successfully!']);
    // }
    public function updateCart(Request $request)
    {
        $validated = $request->validate([
            'quantity' => 'required|array', // Ensure it's an array
            'quantity.*' => 'integer|min:1', // Ensure each quantity is an integer >= 1
        ]);
    
        foreach ($validated['quantity'] as $id => $quantity) {
            $cartItem = ShoppingCart::find($id);
            $variant = $cartItem ? ProductVariant::find($cartItem->variant_id) : null;
    
            if ($variant && $quantity > $variant->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot update quantity for {$variant->product->name}. Only {$variant->stock} left in stock."
                ], 400);
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
