<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;

class IndexController extends BaseController
{
    public function welcome()
    {
        $recommended_products = Product::whereNotNull('images')->inRandomOrder()->take(6)->get();
        $on_sales = Product::whereNotNull('images')->inRandomOrder()->take(6)->get();
        $most_populars = Product::whereNotNull('images')->inRandomOrder()->take(6)->get();
        $special_offer = Product::whereNotNull('images')->inRandomOrder()->first();
        return view('welcome', compact('recommended_products', 'on_sales', 'most_populars', 'special_offer'));
    }
}
