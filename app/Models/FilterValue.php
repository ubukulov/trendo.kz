<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Filter;

class FilterValue extends Model
{
    protected $table = 'filter_values';

    protected $fillable = [
        'title', 'alias', 'filter_id', 'filter_uuid', 'sort_order', 'filter_group_uuid', 'value_id'
    ];

    public static function exists($alias)
    {
        $result = FilterValue::whereAlias($alias)->first();
        return ($result) ? false : true;
    }

    public static function getFilterIdByFilterUuid($filter_uuid)
    {
        $filter = Filter::where(['uuid' => $filter_uuid])->first();
        return ($filter) ? $filter->id : 0;
    }
}
