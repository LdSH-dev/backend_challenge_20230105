<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'ApiController@info');
Route::put('/products/{code}', 'ProductController@update');
Route::delete('/products/{code}', 'ProductController@destroy');
Route::get('/products/{code}', 'ProductController@show');
Route::get('/products', 'ProductController@index');

