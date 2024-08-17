<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'App\Root\Controller', 'domain' => Config::get('app.url')], function()
{
    Route::get('/temp/{filename}', 'TempController@get');
});

Route::group(['namespace' => 'Controllers\Play\Minecraft', 'domain' => 'play.'.Config::get('app.url')], function() {
    Route::get('/production/info/{name}', 'ProductionController@info');
    Route::get('/production/create/{name}/{type}/{ver}', 'ProductionController@create');

    Route::get('/package/download/{prod}/{build}', 'PackageController@download');
    Route::get('/package/create/{prod}/{ver}', 'PackageController@create');
});
