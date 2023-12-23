<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    public function checkout()
    {
        return redirect()->route('cart.view');
    }

    public function session(Request $request)
    {
        // Assuming you have Sanctum or Passport set up for API authentication
        $user = Auth::user();

        // Set your Stripe API key
        \Stripe\Stripe::setApiKey(config('stripe.sk'));

        $cartItems = $request->input('products');
        $lineItems = [];

        foreach ($cartItems as $product) {
            $product = json_decode($product, true);
        
            if (isset($product['product']) && is_array($product['product'])) {
                $lineItems[] = [
                    'price_data' => [
                        'currency'     => 'USD',
                        'product_data' => [
                            'name' => isset($product['product']['product_name']) ? $product['product']['product_name'] : 'Unnamed Product',
                        ],
                        'unit_amount'  => isset($product['product']['price']) ? $product['product']['price'] * 100 : 0,
                    ],
                    'quantity'   => isset($product['quantity']) ? $product['quantity'] : 1,
                ];
            }
        }
        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items'          => $lineItems,
                'mode'                => 'payment',
                'success_url'         => url('/success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'          => url('/checkout'),
                'metadata'            => [
                    'user_id' => $user->id, 
                ],
            ]);


            return redirect()->away($session->url);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error("Stripe Session Creation Error: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('stripe.sk'));
    
        $sessionId = $request->input('session_id');
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
    
        // Convert StripeObject to an array for logging
        $metadataArray = json_decode(json_encode($session->metadata), true);
    
        // Debugging
        Log::info('Stripe Session Metadata:', $metadataArray);
    
        $userId = $metadataArray['user_id'] ?? null;
    
        // Debugging
        Log::info('User ID from Metadata: ' . $userId);
    
        // Save transaction details
        $transaction = new Transaction([
            'user_id'          => $userId,
            'payment_intent_id' => $session->payment_intent,
            'amount'           => $session->amount_total / 100, // Assuming the amount is in cents
        ]);
    
        $transaction->save();
    
        // Clear the cart
        $this->clearCart($userId);
    
        return redirect()->route('cart.view')->with('success', 'You have just completed your payment! Thank you!');
    }
    

    public function clearCart($userId)
    {
    
        Cart::where('user_id', $userId)->delete();
    }
    
    
}
