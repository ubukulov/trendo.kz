<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class IndexController extends BaseController
{
    public function welcome()
    {
        return view('welcome');
    }
}
