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
        $cartHash = session()->get('cart_hash');

        if ($userId) {
            $cartItems = ShoppingCart::with('product')->where('user_id', $userId)->get();
        } else {
            $cartItems = ShoppingCart::with('product')
                ->where(function ($query) use ($sessionId, $cartHash) {
                    $query->where('session_id', $sessionId)
                        ->orWhere('cart_hash', $cartHash);
                })->get();
        }

        $total = $cartItems->sum(fn($item) => ($item->product->price ?? $item->price) * $item->quantity);

        return view('landing.cart.index', compact('cartItems', 'total'));
    }


    // public function index(Request $request)
    // {
    //     $userId = auth()->id();
    //     $sessionId = session()->get('cart_session_id');

    //     if ($userId) {
    //         $cartItems = ShoppingCart::with('product')->where('user_id', $userId)->get();
    //     } else {
    //         $cartItems = ShoppingCart::with('product')->where('session_id', $sessionId)->get();
    //     }

    //     $total = $cartItems->sum(fn($item) => ($item->product->price ?? $item->price) * $item->quantity);

    //     return view('landing.cart.index', compact('cartItems', 'total'));
    // }
    public function addToCart(Request $request)
    {
        $variant = ProductVariant::with('product')->findOrFail($request->variant_id);
        $userId = auth()->id();

        // Generate session ID if not set
        $sessionId = session()->get('cart_session_id') ?? session()->getId();
        session()->put('cart_session_id', $sessionId);

        // Generate cart hash if not set
        $cartHash = session()->get('cart_hash') ?? Str::uuid()->toString();
        session()->put('cart_hash', $cartHash);

        $price = $variant->price;

        // Query existing cart items using both session_id and cart_hash
        $existingCartItem = ShoppingCart::where('product_id', $variant->product_id)
            ->where('variant_id', $variant->id)
            ->where(function ($query) use ($userId, $sessionId, $cartHash) {
                $query->when($userId, function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                }, function ($q) use ($sessionId, $cartHash) {
                    $q->where('session_id', $sessionId)
                        ->orWhere('cart_hash', $cartHash);
                });
            })
            ->first();

        if ($existingCartItem) {
            $existingCartItem->quantity += $request->quantity ?? 1;
            $existingCartItem->price = $price;
            $existingCartItem->save();
        } else {
            $cartItem = new ShoppingCart();
            $cartItem->user_id = $userId;
            $cartItem->session_id = $userId ? null : $sessionId;
            $cartItem->cart_hash = $cartHash;
            $cartItem->product_id = $variant->product_id;
            $cartItem->variant_id = $variant->id;
            $cartItem->quantity = $request->quantity ?? 1;
            $cartItem->price = $price;
            $cartItem->save();
        }

        return response()->json(['message' => 'Product added to cart successfully!']);
    }




    // public function addToCart(Request $request)
    // {
    //     $variant = ProductVariant::with('product')->findOrFail($request->variant_id);
    //     $userId = auth()->id();

    //     $sessionId = session()->get('cart_session_id') ?? Str::uuid()->toString();
    //     session()->put('cart_session_id', $sessionId);

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

    //     return response()->json(['message' => 'Product added to cart successfully!']);
    // }
    public function cartShow()
    {
        $userId = auth()->id();
        $sessionId = session()->get('cart_session_id');
        $cartHash = session()->get('cart_hash');

        $cartItems = ShoppingCart::with('product.attachments')
            ->when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }, function ($query) use ($sessionId, $cartHash) {
                $query->where('session_id', $sessionId)
                    ->orWhere('cart_hash', $cartHash);
            })->get();

        $total = $cartItems->sum(fn($item) => ($item->product->price ?? $item->price) * $item->quantity);

        return view('landing.cart.index', compact('cartItems', 'total'));
    }


    // public function cartShow()
    // {
    //     $userId = auth()->id();
    //     $sessionId = session()->get('cart_session_id');
    //     $cartItems = ShoppingCart::with('product.attachments')->get();
    //     $productimage=Product::with(['variants.attachments'])->get();

    //     $cartItems = ShoppingCart::with('product')->when($userId, function ($query) use ($userId) {
    //         $query->where('user_id', $userId);
    //     }, function ($query) use ($sessionId) {
    //         $query->where('session_id', $sessionId);
    //     })->get();
    //     dd($productimage);
    //     $total = $cartItems->sum(fn($item) => ($item->product->price ?? $item->price) * $item->quantity);

    //     return view('landing.cart.index', compact('cartItems', 'total'));
    // }
    public function updateCart(Request $request)
    {
        $userId = auth()->id();
        $sessionId = session()->get('cart_session_id');
        $cartHash = session()->get('cart_hash');

        if ($userId) {
            foreach ($request->quantity as $id => $quantity) {
                $cartItem = ShoppingCart::where('user_id', $userId)->find($id);
                if ($cartItem && $quantity > 0) {
                    $cartItem->quantity = $quantity;
                    $cartItem->save();
                }
            }
        } else {
            foreach ($request->quantity as $id => $quantity) {
                $cartItem = ShoppingCart::where('session_id', $sessionId)
                    ->orWhere('cart_hash', $cartHash)
                    ->find($id);
                if ($cartItem && $quantity > 0) {
                    $cartItem->quantity = $quantity;
                    $cartItem->save();
                }
            }
        }

        return redirect()->route('cart.show')->with('success', 'Cart updated successfully!');
    }



    // public function updateCart(Request $request)
    // {

    //     if (auth()->check()) {
    //         $userId = auth()->id();
    //         foreach ($request->quantity as $id => $quantity) {
    //             $cartItem = ShoppingCart::where('user_id', $userId)->find($id);
    //             if ($cartItem && $quantity > 0) {
    //                 $cartItem->quantity = $quantity;
    //                 $cartItem->save();
    //             }
    //         }
    //     }

    //     else {
    //         $cart = session()->get('cart', []);
    //         foreach ($request->quantity as $id => $quantity) {
    //             if (isset($cart[$id])) {
    //                 if ($quantity > 0) {
    //                     $cart[$id]['quantity'] = $quantity;
    //                 } else {
    //                     unset($cart[$id]);
    //                 }
    //             }
    //         }
    //         session()->put('cart', $cart);
    //     }

    //     return redirect()->route('cart.show')->with('success', 'Cart updated successfully!');
    // }



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
