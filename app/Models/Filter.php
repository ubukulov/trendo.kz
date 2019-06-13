<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FilterGroup;
use Illuminate\Support\Facades\DB;

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

    public function values()
    {
        $filter_id = $this->id;
        $values = DB::select("SELECT
                                        fv.title,fv.alias
                                        FROM filter_values fv
                                        INNER JOIN filter_value_products fvp ON fvp.filter_value_id=fv.id
                                        INNER JOIN product_vendor_products pvp ON pvp.product_id=fvp.product_id
                                        WHERE fv.filter_id='$filter_id' AND pvp.quantity>0 GROUP BY fv.alias");
        return $values;
    }
}
