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

###### TEST ######
Route::get('admin/cats/create', 'CategoryController@create');
Route::post('cats/store', 'CategoryController@store');
###### TEST ######