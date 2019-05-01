<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $products = Product::getProducts();
        return view('category/index', compact('products'));
    }
}
