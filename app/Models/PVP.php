<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PVP extends Model
{
    protected $table = 'product_vendor_products';

    protected $fillable = [
        'product_id', 'vendor_id', 'quantity', 'price', 'base_price', 'product_title', 'created_at', 'updated_at',
        'article'
    ];
}
