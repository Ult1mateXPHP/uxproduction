<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Controllers\Play\Minecraft\TelegramController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'App\Root\Controller', 'domain' => Config::get('app.url')], function()
{
    Route::get('/temp/{filename}', 'TempController@get');
});

Route::group(['namespace' => 'Controllers\Play\Minecraft', 'domain' => 'play.'.Config::get('app.url')], function() {
    Route::post('/bot/webhook', function (TelegramController $telegramController) {
        $telegramController->webhook();
        return response()->json(['success' => true]);
    });
    Route::post('/bot/handler', 'TelegramController@handler');
    Route::post('/bot/broadcast', 'TelegramController@broadcast');

    Route::get('/production/info/{name}', 'ProductionController@info');
    Route::get('/production/create/{name}/{type}/{ver}', 'ProductionController@create');

    Route::get('/package/download/{prod}/{build}', 'PackageController@download');
    Route::get('/package/create/{prod}/{ver}', 'PackageController@create');
});
