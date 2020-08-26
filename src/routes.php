<?php


use Illuminate\Support\Facades\Route;

Route::prefix('/laravel-ses')->group(function() {
    // Receive SNS notifications
    Route::post('notification/bounce', 'DavidNeal\LaravelSesTracker\Controllers\BounceController@bounce');
    Route::post('notification/delivery', 'DavidNeal\LaravelSesTracker\Controllers\DeliveryController@delivery');
    Route::post('notification/complaint', 'DavidNeal\LaravelSesTracker\Controllers\ComplaintController@complaint');

    // User tracking
    Route::get('beacon/{beaconIdentifier}', 'DavidNeal\LaravelSesTracker\Controllers\OpenController@open');
    Route::get('link/{linkId}', 'DavidNeal\LaravelSesTracker\Controllers\LinkController@click');
});
