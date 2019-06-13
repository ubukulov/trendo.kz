<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\FilterGroup;
use App\Models\Filter;
use App\Models\FilterValue;
use App\Models\FilterValueProduct;
use App\Models\CategoryFilter;
class EtradeFilter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etrade:filters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filter_groups = DB::select("SELECT * FROM etrade_attribute_block_temp");
        foreach($filter_groups as $filter_group){
            if (FilterGroup::exists($filter_group->code)) {
                FilterGroup::create([
                    'title' => $filter_group->name, 'alias' => $filter_group->code, 'status' => $filter_group->status,
                    'uuid' => $filter_group->uuid, 'sort_order' => $filter_group->sort_order
                ]);
            }
        }
        unset($filter_groups);
        $this->info("Фильтр группы добавлены");

        $filters = DB::select("SELECT * FROM etrade_attribute_temp");
        foreach ($filters as $filter) {
            if (Filter::exists($filter->code)) {
                $filter_group_id = Filter::getFilterGroupByUuid($filter->block_uuid);
                Filter::create([
                    'title' => $filter->name, 'alias' => $filter->code, 'sort_order' => $filter->sort_order,
                    'filter_group_uuid' => $filter->block_uuid, 'uuid' => $filter->uuid,
                    'status' => $filter->status, 'filter_group_id' => $filter_group_id
                ]);
            }
        }
        unset($filters);
        $this->info("Фильтры успешно добавлены");

        $filter_all_values = DB::select("SELECT * FROM etrade_attribute_value_temp");
        foreach($filter_all_values as $filter_all_value) {
            if (FilterValue::exists($filter_all_value->code)) {
                $filter_id = FilterValue::getFilterIdByFilterUuid($filter_all_value->attribute_uuid);
                FilterValue::create([
                    'title' => $filter_all_value->attribute_value, 'alias' => $filter_all_value->code,
                    'filter_uuid' => $filter_all_value->attribute_uuid, 'filter_id' => $filter_id, 'value_id' => $filter_all_value->attribute_value_id
                ]);
            }
        }
        unset($filter_all_values);
        $this->info("Значение фильтров добавлен успешно");

        $filter_values = DB::select("SELECT * FROM etrade_product_attribute_temp");
        foreach ($filter_values as $filter_value) {
            $filter_val = FilterValue::where(['value_id' => $filter_value->attribute_value_id])->first();
            if ($filter_val) {
                $filter_val->sort_order = $filter_value->sort_order;
                $filter_val->filter_group_uuid = $filter_value->attribute_block_uuid;
                $filter_val->save();

                if (FilterValueProduct::exists($filter_value->product_uuid, $filter_val->id)) {
                    FilterValueProduct::create([
                        'product_id' => $filter_value->product_uuid, 'filter_value_id' => $filter_val->id
                    ]);
                }
            }
        }

        unset($filter_values);

        /*--------------------*/
        $etrade_category_filters = DB::select("SELECT * FROM etrade_attribute_category_temp");
        foreach($etrade_category_filters as $etrade_category_filter) {
            $filter = Filter::where(['uuid' => $etrade_category_filter->attribute_uuid])->first();
            if ($filter) {
                $category_id = $etrade_category_filter->category_uuid;
                $filter_id = $filter->id;
                if (!CategoryFilter::exists($category_id, $filter_id)) {
                    CategoryFilter::create([
                        'category_id' => $category_id, 'filter_id' => $filter_id
                    ]);
                }
            }
        }

        $this->info("Завершен успешно");
    }
}
