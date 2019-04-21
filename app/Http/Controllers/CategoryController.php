<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $products = Product::getProducts(1);
        return view('category/index', compact('products'));
    }
}
