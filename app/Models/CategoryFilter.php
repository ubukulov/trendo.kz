<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryFilter extends Model
{
    protected $table = 'category_filters';

    protected $fillable = ['category_id', 'filter_id'];

    protected $dates = ['created_at', 'updated_at'];

    public static function exists($category_id, $filter_id)
    {
        $result = CategoryFilter::where(['category_id' => $category_id, 'filter_id' => $filter_id])->first();
        return ($result) ? true : false;
    }
}
