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

// Debug Sentry.IO
Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});

// API
Route::get('slots/{payload}/{token}', 'SlotController@present')
        ->where(['payload'  => '[0-9,]+', 'name' => '[A-Za-z0-9]+']);
Route::resource('slots', 'SlotController');

// SPA
Route::get('/{any}', 'SinglePageController@index')->where('any', '.*');
