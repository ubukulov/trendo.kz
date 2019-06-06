<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\ShoppingCart;

class CheckoutController extends BaseController
{
    public function __construct()
    {
        $this->page = 'checkout';
        parent::__construct();
    }

    public function index()
    {
        if (\Auth::check()) {
            $cartItems = UserCart::all();
        } else {
            $cartItems = ShoppingCart::getCartItems();
        }
        return view('checkout.index', compact('cartItems'));
    }
}
