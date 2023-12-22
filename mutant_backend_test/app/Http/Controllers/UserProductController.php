<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\Http\Request;

class UserProductController extends Controller
{
    public function main()
    {
        $products = Product::paginate(20);

        return view('products', compact('products'));
    }
}
