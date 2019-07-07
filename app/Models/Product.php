<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use Sluggable;

    protected $NO_IMAGE = '/assets/images/products/noImage.jpg';
    protected $IMAGE_PATH = '/uploads/products/';
    protected $large = 'large_600x600_';
    protected $small = 'small_250x232_';
    protected $mini = 'mini_180x180_';

    protected $fillable = [
        'title', 'alias', 'category_id', 'brand_id', 'keywords', 'description', 'full_description',
        'images', 'created_at', 'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function vendors()
    {
        return $this->belongsToMany('App\Models\Vendor', 'product_vendor_products', 'product_id', 'vendor_id')->withPivot("id", "base_price", "price", "quantity", "article")->orderBy("product_vendor_products.price");
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'alias' => [
                'source' => 'title'
            ]
        ];
    }

    public function url()
    {
        $url = $this->alias . '/' . $this->id;
        return url($url);
    }

    /**
     * Метод по категорую получает список товаров
     * @return object
     */
    public static function getProducts()
    {
        $products = Product::where('product_vendor_products.quantity', '!=', 0)
                ->select('products.*', 'product_vendor_products.quantity', 'product_vendor_products.base_price')
                ->join('product_vendor_products', 'product_vendor_products.product_id', '=', 'products.id')
                ->orderBy('products.id')
                ->paginate(18);
        return $products;
    }

    /**
     *
     */
    public static function getProduct($id)
    {
        $product = Product::where('product_vendor_products.quantity', '!=', 0)->where('products.id', '=', $id)
            ->select('products.*', 'product_vendor_products.*')
            ->join('product_vendor_products', 'product_vendor_products.product_id', '=', 'products.id')
            ->first();
        return $product;
    }

    public function getImage($all = false)
    {
        if (empty($this->images) || is_null($this->images)) {
            return url($this->NO_IMAGE);
        } else {
            if ($all) {
                $images_array = [];
                $images = json_decode($this->images, true);
                foreach ($images as $filename) {
                    $image_name = substr($filename, strrpos($filename, "/")+1);
                    $path = substr($filename, 0, strrpos($filename, "/")+1);
                    $images_array[] = $this->IMAGE_PATH.$path.$this->mini.$image_name;
                }
                return $images_array;
            } else {
                if (is_array($this->images)) {
                    $images = json_decode($this->images, true);
                    $image_name = substr($images[0], strrpos($images[0], "/")+1);
                    $path = substr($images[0], 0, strrpos($images[0], "/")+1);
                    $path_to_image = $this->IMAGE_PATH.$path.$this->small.$image_name;
                    return url($path_to_image);
                } elseif (is_string($this->images)) {
                    $images = json_decode($this->images, true);
                    $image_name = substr($images[0], strrpos($images[0], "/")+1);
                    $path = substr($images[0], 0, strrpos($images[0], "/")+1);
                    $path_to_image = $this->IMAGE_PATH.$path.$this->small.$image_name;
                    return url($path_to_image);
                } else {
                    return url($this->IMAGE_PATH.$this->images);
                }

            }

        }
    }

    /**
     * Возвращает список товаров по определенному категорию
     * @param $category_id integer
     * @return mixed
     */
    public static function getProductsByCategory($category_id)
    {
        if (\App::environment() == "production") {
            $products = Product::where('product_vendor_products.quantity', '!=', 0)
                ->select('products.*', 'product_vendor_products.*')
                ->join('product_vendor_products', 'product_vendor_products.product_id', '=', 'products.id')
                ->orderBy('products.id')
                ->where(['products.category_id' => $category_id])
                ->whereNotNull('products.images')
                ->paginate(18);
        } else {
            $products = Product::where('product_vendor_products.quantity', '!=', 0)
                ->select('products.*', 'product_vendor_products.*')
                ->join('product_vendor_products', 'product_vendor_products.product_id', '=', 'products.id')
                ->orderBy('products.id')
                ->where(['products.category_id' => $category_id])
                ->paginate(18);
        }

        return $products;
    }

    public function getPrice()
    {
        $pvp = PVP::where('product_id', $this->id)->first();
        return $pvp->price;
    }

    public function getFilters()
    {
        $product_id = $this->id;
        $result = DB::select("SELECT 
                                    f.title as f_title, fv.title as fv_title  
                                    FROM filter_value_products fvp
                                    INNER JOIN filter_values fv ON fv.id=fvp.filter_value_id
                                    INNER JOIN filters f ON f.id=fv.filter_id
                                    WHERE fvp.product_id='$product_id' LIMIT 5    
        ");
        return $result;
    }
    public function getFiltersAll()
    {
        $product_id = $this->id;
        $result = DB::select("SELECT 
                                    f.title as f_title, fv.title as fv_title  
                                    FROM filter_value_products fvp
                                    INNER JOIN filter_values fv ON fv.id=fvp.filter_value_id
                                    INNER JOIN filters f ON f.id=fv.filter_id
                                    WHERE fvp.product_id='$product_id' LIMIT 25    
        ");
        return $result;
    }
}
