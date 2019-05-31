<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexController@welcome')->name('home');

Route::get('/{alias}', 'CategoryController@index')->name('catalog.view');
Route::get('/{alias}/{id}', 'ProductController@index')->name('product.index');
Route::get('/get_products', 'ProductController@get');
Route::get('/login', 'AuthController@showLogin')->name('showLogin');

// UserCart
Route::group(['prefix' => 'cart'], function() {
    Route::get('/index/list', 'CartController@index')->name('cart.index');
    Route::get('add/{product_id}', 'CartController@addToCart')->name('cart.add');
    Route::post('add', 'CartController@add')->name('cart.add2');
    Route::get('/delete/{product_id}', 'CartController@delete')->name('cart.delete');
});

###### TEST ######
Route::get('admin/cats/create', 'CategoryController@create');
Route::post('cats/store', 'CategoryController@store');
###### TEST ######
