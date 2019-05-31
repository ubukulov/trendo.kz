<?php

namespace App\Classes;

use App\Models\UserCart;
class ShoppingCart
{
    public static function add($product_id, $quantity = 1)
    {
        $user = \Auth::check() ?: null;
        if ($user) {
            UserCart::create([
                'user_id' => $user->id, 'product_id' => $product_id, 'quantity' => $quantity
            ]);
        } else {
            $data = \Session::get('carts') ?: [];
            if (array_key_exists($product_id, $data)) {
                $data[$product_id]['quantity'] = $data[$product_id]['quantity'] + $quantity;
            } else {
                $data[$product_id]['quantity'] = $quantity;
            }
            self::setCartCache($data);
        }
    }

    public static function setCartCache($data)
    {
        \Session::put('carts', $data);
    }

    public static function getCartItems()
    {
        if (\Auth::check()) {

        } else {
            $carts = [];
            foreach(\Session::get('carts') as $key=>$item) {
                $cartItem = new UserCart();
                $cartItem->product_id = $key;
                $cartItem->quantity = $item['quantity'];
                $carts[] = $cartItem;
            }
            return $carts;
        }
    }

    public static function getCountItems()
    {
        return count(self::getCartItems());
    }

    public static function getTotalPrice()
    {
        $sum = 0;
        foreach(self::getCartItems() as $cartItem) {
            $sum += $cartItem->quantity * $cartItem->product->getPrice();
        }
        return $sum;
    }

    public static function deleteFromCartItem($product_id)
    {
        if (\Auth::check()) {

        } else {
            $data = \Session::get('carts');
            if (array_key_exists($product_id, $data)) {
                unset($data[$product_id]);
                \Session::put('carts', $data);
            }
        }
    }
}
