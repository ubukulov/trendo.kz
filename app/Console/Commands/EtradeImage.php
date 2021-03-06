<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EtradeImage extends Command
{
    protected $IMAGE_PATH = '/uploads/products/';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etrade:image';

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
        DB::update("UPDATE products SET images=null");

        $etrade_images = DB::select("
                  SELECT 
                  image_temp.item_uuid, image_temp.image 
                  FROM etrade_image_temp image_temp
                  INNER JOIN products p ON p.id=image_temp.item_uuid
                  WHERE image_temp.row_type='product'
        ");
        $image_array = [];
        $count = 0;
        foreach($etrade_images as $etrade_image) {
            if (array_key_exists($etrade_image->item_uuid, $image_array) && $count <= 5 && file_exists(public_path($this->IMAGE_PATH.$etrade_image->image))) {
                $image_array[$etrade_image->item_uuid][] = $etrade_image->image;
                $count++;
            } else {
                if (file_exists(public_path($this->IMAGE_PATH.$etrade_image->image))) {
                    $image_array[$etrade_image->item_uuid][] = $etrade_image->image;
                    $count++;
                }
            }
        }
        foreach($image_array as $product_id=>$array) {
            $json_images = json_encode($array);
            DB::update("UPDATE products SET images='$json_images' WHERE id='$product_id'");
        }
        $this->info("Процесс обновление картинок завершен");
    }
}
