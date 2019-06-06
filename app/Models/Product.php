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
        return $this->belongsToMany('App\Models\Vendor', 'product_vendor_products', 'product_id', 'vendor_id');
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
                    $imagePath = $this->IMAGE_PATH.$filename;
                    $images_array[] = $imagePath;
                }
                return $images_array;
            } else {
                if (is_array($this->images)) {
                    $images = json_decode($this->images, true);
                    return url($this->IMAGE_PATH.$images[0]);
                } elseif (is_string($this->images)) {
                    $images = json_decode($this->images, true);
                    return url($this->IMAGE_PATH.$images[0]);
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
                                    f.f_title, fv.fv_title  
                                    FROM filter_value_products fvp
                                    INNER JOIN filter_values fv ON fv.id=fvp.filter_value_id
                                    INNER JOIN filters f ON f.id=fv.filter_id
                                    WHERE fvp.product_id='$product_id' LIMIT 5    
        ");
        return $result;
    }
}
