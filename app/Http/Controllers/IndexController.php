<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class IndexController extends Controller
{
    public function welcome()
    {
        $cats = Category::get()->toTree();
        return view('welcome', compact('cats'));
    }
}
