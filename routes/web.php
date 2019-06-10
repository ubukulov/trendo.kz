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

Route::get('/catalog/{alias}', 'CategoryController@index')->name('catalog.view');
Route::get('/{alias}/{id}', 'ProductController@index')->name('product.index');
Route::get('/get_products', 'ProductController@get');
Route::get('/login', 'AuthController@showLogin')->name('showLogin');
Route::post('/login', 'AuthController@authenticate')->name('authenticate');
Route::post('/registration', 'AuthController@registration')->name('registration');
Route::get('/logout', 'AuthController@logout')->name('logout');

// UserCart
Route::group(['prefix' => 'cart'], function() {
    Route::get('/', 'CartController@index')->name('cart.index');
    Route::get('add/{product_id}', 'CartController@addToCart')->name('cart.add');
    Route::post('add', 'CartController@add')->name('cart.add2');
    Route::get('/delete/{product_id}', 'CartController@delete')->name('cart.delete');
});

// Checkout
Route::group(['prefix' => 'checkout'], function(){
    Route::get('/', 'CheckoutController@index')->name('checkout.index');
    Route::post('/store', 'CheckoutController@store')->name('checkout.store');
});

###### TEST ######
Route::get('admin/cats/create', 'CategoryController@create');
Route::post('cats/store', 'CategoryController@store');
###### TEST ######
