<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FilterGroup;

class Filter extends Model
{
    protected $fillable = [
        'title', 'alias', 'sort_order', 'filter_group_id', 'filter_group_uuid', 'uuid', 'status'
    ];

    protected $dates = ['created_at', 'updated_at'];

    public static function exists($alias)
    {
        $result = Filter::whereAlias($alias)->first();
        return ($result) ? false : true;
    }

    public static function getFilterGroupByUuid($filter_group_uuid)
    {
        $filter_group = FilterGroup::where(['uuid' => $filter_group_uuid])->first();
        return ($filter_group) ? $filter_group->id : 0;
    }
}
