<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index($alias)
    {
        $cats = Category::get()->toTree();
        $products = Product::getProducts();
        $category = Category::whereAlias($alias)->first();
        return view('category/index', compact('products', 'cats', 'category'));
    }

    public function create()
    {
//        $result = Category::withDepth()->find(3);
//
//        $depth = $result->depth;
//        dd($depth);
        $cats = Category::where('id', '>', 169)->get()->toTree();
        return view('cats-create', compact('cats'));
    }

    public function store(Request $request)
    {
        if ($request->input('parent_id') == 0) {
            $node = new Category($request->all());
            $node->saveAsRoot();
            return redirect()->back();
        } else {
            $node = new Category($request->all());
            $parent = Category::find($request->input('parent_id'));
            $node->appendToNode($parent)->save();
            return redirect()->back();
        }
    }
}
