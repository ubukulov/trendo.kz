<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use View;

class BaseController extends Controller
{
    protected $cats;
    protected $subCategories;
    protected $page = 'main-page';

    public function __construct()
    {
        $this->cats = Category::get()->toTree();
        $this->subCategories = Category::withDepth()->having('depth', '=', 2)->get();
        $recommended_products = Product::whereNotNull('images')->inRandomOrder()->take(6)->get();
        $on_sales = Product::whereNotNull('images')->inRandomOrder()->take(6)->get();
        $most_populars = Product::whereNotNull('images')->inRandomOrder()->take(6)->get();
        View::share('cats', $this->cats);
        View::share('subCategories', $this->subCategories);
        View::share('page', $this->page);
        View::share('recommended_products', $recommended_products);
        View::share('on_sales', $on_sales);
        View::share('most_populars', $most_populars);
    }
}
