<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterValueProduct extends Model
{
    protected $table = 'filter_value_products';

    protected $fillable = [
        'product_id', 'filter_value_id'
    ];

    protected $dates = ['created_at', 'updated_at'];

    public static function exists($product_id, $filter_value_id)
    {
        $result = FilterValueProduct::where(['product_id' => $product_id, 'filter_value_id' => $filter_value_id])->first();
        return ($result) ? false : true;
    }
}
