<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Product extends Model
{
    use Sluggable;

    protected $NO_IMAGE = '/assets/images/products/noImage.jpg';

    protected $fillable = [
        'title', 'alias', 'category_id', 'brand_id', 'keywords', 'description', 'full_description',
        'images', 'created_at', 'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
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
            ->select('products.*', 'product_vendor_products.quantity', 'product_vendor_products.base_price', 'product_vendor_products.article')
            ->join('product_vendor_products', 'product_vendor_products.product_id', '=', 'products.id')
            ->first();
        return $product;
    }

    public function getImage()
    {
        if (empty($this->images) || is_null($this->images)) {
            return url($this->NO_IMAGE);
        } else {
            return $this->images;
        }
    }

    /**
     * Возвращает список товаров по определенному категорию
     * @param $category_id integer
     * @return mixed
     */
    public static function getProductsByCategory($category_id)
    {
        $products = Product::where('product_vendor_products.quantity', '!=', 0)
            ->select('products.*', 'product_vendor_products.quantity', 'product_vendor_products.base_price', 'product_vendor_products.article')
            ->join('product_vendor_products', 'product_vendor_products.product_id', '=', 'products.id')
            ->orderBy('products.id')
            ->where(['products.category_id' => $category_id])
            ->paginate(18);
        return $products;
    }
}
