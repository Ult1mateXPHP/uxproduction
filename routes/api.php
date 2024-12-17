<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'Controllers\Root', 'domain' => Config::get('app.url')], function()
{
    Route::post('/register', 'AuthController@register');

    Route::get('/temp/{filename}', 'TempController@get');
});

Route::group(['namespace' => 'Controllers\Play\Minecraft', 'domain' => 'play.'.Config::get('app.url')], function() {
    Route::get('/bot/webhook', 'TelegramController@webhook');
    Route::post('/bot/handler', 'TelegramController@handler');
    Route::post('/bot/broadcast', 'TelegramController@broadcast');

    Route::get('/production/info/{name}', 'ProductionController@info');
    Route::get('/production/create/{name}/{type}/{ver}', 'ProductionController@create');

    Route::get('/package/download/', function () {
        return response(null, 400);
    })->name('package.download.reference');

    Route::get('/package/download/{prod}/{build}', 'PackageController@download');
    Route::get('/package/create/{prod}/{ver}', 'PackageController@create');
});
