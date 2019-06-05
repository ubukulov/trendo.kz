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
        View::share('cats', $this->cats);
        View::share('subCategories', $this->subCategories);
        View::share('page', $this->page);
    }
}
