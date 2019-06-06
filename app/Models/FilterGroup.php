<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterGroup extends Model
{
    protected $table = 'filter_groups';

    protected $dates = [
        'created_at', 'updated_at'
    ];

    protected $fillable = [
        'id', 'title', 'alias', 'status', 'uuid', 'sort_order'
    ];

    public static function exists($alias)
    {
        $result = FilterGroup::whereAlias($alias)->first();
        return ($result) ? false : true;
    }
}
