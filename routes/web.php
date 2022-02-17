<?php

use System\Router\Web\Route;

Route::get('/', 'HomeController@index', 'index');
Route::get('/create', 'HomeController@create', 'create');
Route::get('/edit', 'HomeController@edit', 'edit');
Route::get('/ff', 'HomeController@ff', 'edit');