<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{

    public function index($alias, $params = '')
    {
        $category = Category::whereAlias($alias)->first();
        if (!$category) abort(404);
        $products = Product::getProductsByCategory($category->id);
        if (count($category->parents) == 0) {
            return view('category/index', compact( 'products', 'category'));
        } else {
            return view('category/view', compact('category'));
        }
    }

    public function create()
    {
//        $result = Category::withDepth()->find(3);
//
//        $depth = $result->depth;
//        dd($depth);
        $cats = Category::where('id', '>', 38)->get()->toTree();
        return view('cats-create', compact('cats'));
    }

    public function store(Request $request)
    {
        $parent_id = $request->input('parent_id');
        if ($parent_id == 0) {
            $node = new Category($request->all());
            $node->saveAsRoot();
            return redirect()->back();
        } else {
            $node = new Category($request->all());
            $parent = Category::find($parent_id);
            $node->appendToNode($parent)->save();
            if ($request->input('rem') == 1) {
                $rem_cat_id = $parent_id;
            } else {
                $rem_cat_id = 0;
            }
            return redirect()->back()->with('rem_cat_id', $rem_cat_id);
        }
    }
}
