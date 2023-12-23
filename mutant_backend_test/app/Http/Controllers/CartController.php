<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use App\Models\Transaction;

use Illuminate\Http\Request;

class CartController extends Controller
{

    public function addToCart(Product $product)
    {
        $user = auth()->user();

        // Check if the product is already in the cart
        $cartItem = Cart::where('user_id', $user->id)->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Increment quantity if the product is already in the cart
            $cartItem->update(['quantity' => $cartItem->quantity + 1]);
        } else {
            // Add the product to the cart
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        // Calculate total quantity of items in the cart
        $totalQuantity = Cart::where('user_id', $user->id)->sum('quantity');

        // Set the cart count in the session
        session(['cartCount' => $totalQuantity]);

        // Return the updated total quantity
        return response()->json(['totalQuantity' => $totalQuantity]);
    }

    public function viewCart()
    {
        $user = auth()->user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        $totalQuantity = $cartItems->sum('quantity');

        $totalCost = $cartItems->sum(function ($cartItem) {
            return $cartItem->quantity * $cartItem->product->price;
        });

        session(['cartCount' => $totalQuantity]);

        return view('cart.view', compact('cartItems', 'totalQuantity', 'totalCost'));
    }

    public function updateCart(Request $request)
    {
        $productId = $request->input('id');
        $action = $request->input('action');

        $cartItem = Cart::find($productId);

        if (!$cartItem) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        if ($action === 'increment') {
            $cartItem->quantity++;
        } elseif ($action === 'decrement' && $cartItem->quantity > 1) {
            $cartItem->quantity--;
        }

        $cartItem->save();

        return response()->json(['quantity' => $cartItem->quantity]);
    }
    
    
    public function removeProduct($cartItemId)
    {
        $cartItem = Cart::findOrFail($cartItemId);
    
        $cartItem->delete();
    
        return response()->json(['success' => true, 'message' => 'Product removed from cart']);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    
}
