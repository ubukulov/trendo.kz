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
                                        filter_values.id, filter_values.title, filter_values.alias
                                        FROM filter_values
                                        INNER JOIN filter_value_products ON filter_value_products.filter_value_id=filter_values.id
                                        INNER JOIN product_vendor_products ON product_vendor_products.product_id=filter_value_products.product_id
                                        WHERE filter_values.filter_id='$filter_id' AND product_vendor_products.quantity>0 GROUP BY filter_values.id");
        return $values;
    }
}
