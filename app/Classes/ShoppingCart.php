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

        }
    }
}
