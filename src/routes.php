<?php


use Illuminate\Support\Facades\Route;

Route::prefix('/laravel-ses')->group(function() {
    // Receive SNS notifications
    Route::post('notification/bounce', 'andytan07\LaravelSesTracker\Controllers\BounceController@bounce');
    Route::post('notification/delivery', 'andytan07\LaravelSesTracker\Controllers\DeliveryController@delivery');
    Route::post('notification/complaint', 'andytan07\LaravelSesTracker\Controllers\ComplaintController@complaint');

    // User tracking
    Route::get('beacon/{beaconIdentifier}', 'andytan07\LaravelSesTracker\Controllers\OpenController@open');
    Route::get('link/{linkId}', 'andytan07\LaravelSesTracker\Controllers\LinkController@click');

    // Package api
    Route::get('api/has/bounced/{email}', 'andytan07\LaravelSesTracker\Controllers\BounceController@hasBounced');
    Route::get('api/has/complained/{email}', 'andytan07\LaravelSesTracker\Controllers\ComplaintController@hasComplained');
    Route::get('api/stats/batch/{name}', 'andytan07\LaravelSesTracker\Controllers\StatsController@statsForBatch');
    Route::get('api/stats/email/{email}', 'andytan07\LaravelSesTracker\Controllers\StatsController@statsForEmail');
});
