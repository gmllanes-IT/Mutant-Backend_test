<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CheckoutController extends Controller
{
    public function showCheckoutForm(Request $request)
    {

        return View::make('checkout.form');
    }
}
