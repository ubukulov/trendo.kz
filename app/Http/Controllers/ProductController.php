<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index($alias, $id)
    {
        $product = Product::getProduct($id);
        return view('product/index', compact('product'));
    }
}