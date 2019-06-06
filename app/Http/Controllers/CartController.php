<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\ShoppingCart;
use App\Models\UserCart;

class CartController extends BaseController
{
    public function __construct()
    {
        $this->page = 'cart';
        parent::__construct();
    }

    public function index()
    {
	$etrade_product_attribute = \DB::select("SELECT * FROM etrade_product_attribute_temp WHERE product_uuid=1637");
	$etrade_attribute_temp = \DB::select("SELECT * FROM etrade_attribute_temp");
	$etrade_attribute_block_temp = \DB::select("SELECT * FROM etrade_attribute_block_temp");
	$et = \DB::select("SELECT * FROM etrade_attribute_value_temp WHERE attribute_uuid='297271834'");
	dd($etrade_product_attribute, $etrade_attribute_temp, $etrade_attribute_block_temp, $et);
        if (\Auth::check()) {
            $cartItems = UserCart::all();
        } else {
            $cartItems = ShoppingCart::getCartItems();
        }

        return view('cart.index', compact('cartItems'));
    }

    public function addToCart($product_id)
    {
        ShoppingCart::add($product_id);
        return redirect()->route('cart.index');
    }

    public function add(Request $request)
    {
        $quantity = abs($request->input('quantity'));
        $product_id = (int) $request->input('product_id');
        ShoppingCart::add($product_id, $quantity);
        return redirect()->route('cart.index');
    }

    public function delete($product_id)
    {
        ShoppingCart::deleteFromCartItem($product_id);
        return redirect()->back();
    }
}
