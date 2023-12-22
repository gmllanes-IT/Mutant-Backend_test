<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

use App\Models\Cart;
use App\Models\Transaction;

class PaymentController extends Controller
{
    public function showPaymentForm(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $totalPrice = $this->calculateTotalPrice($this->getCartItems($request->user()->id));

            $paymentIntent = PaymentIntent::create([
                'amount' => $totalPrice * 100, 
                'currency' => 'usd', 
            ]);

            $clientSecret = $paymentIntent->client_secret;

            return View::make('payment.form', compact('clientSecret'));
        } catch (\Exception $e) {
            return Redirect::route('payment.error');
        }
    }

    public function processPayment(Request $request)
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $this->calculateTotalPrice($this->getCartItems($request->user()->id)) * 100, 
                'currency' => 'usd', 
            ]);
    
            $transaction = new Transaction();
            $transaction->user_id = $request->user()->id;
            $transaction->payment_intent_id = $paymentIntent->id; 
            $transaction->amount = $this->calculateTotalPrice($this->getCartItems($request->user()->id));
            $transaction->save();
    
            $this->clearCart($request->user()->id);
    
            return Redirect::route('payment.success');
        } catch (\Exception $e) {
            return Redirect::route('payment.error');
        }
    }

    public function paymentSuccess()
    {
        return View::make('payment.success');
    }

    public function paymentError()
    {
        return View::make('payment.error');
    }

    private function calculateTotalPrice($cartItems)
    {
        $totalPrice = 0;
        foreach ($cartItems as $cartItem) {
            $totalPrice += $cartItem['quantity'] * $cartItem['product']['price'];
        }
        return $totalPrice;
    }

    private function getCartItems($userId)
    {
        return Cart::where('user_id', $userId)->with('product')->get();
    }

    private function clearCart($userId)
    {
        Cart::where('user_id', $userId)->delete();
    }

}
