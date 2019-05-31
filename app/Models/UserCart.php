<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
    protected $table = 'carts';

    protected $fillable = [
        'user_id', 'product_id', 'quantity'
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }
}
