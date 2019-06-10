<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = [
        'user_id', 'courier_id', 'city_id', 'payment_id', 'delivery_id', 'phone', 'address', 'status', 'delivery_cost', 'order_notes'
    ];
}
