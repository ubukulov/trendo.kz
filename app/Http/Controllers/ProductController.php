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

    public function get()
    {
        $products = Product::where('category_id', '=', 1)
                    ->join('product_vendor_products', 'product_vendor_products.product_id', '=', 'products.id')
                    ->limit(20)
                    ->get();
        return response()->json($products);
    }
}