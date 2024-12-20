<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::any('/{path?}', function () {
    return view('app');
})->where('path', '(.*)');
