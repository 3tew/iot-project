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
Route::get('slots/{id}/present', 'SlotController@present')->where('id', '[0-9]+');
Route::get('slots/{id}/empty', 'SlotController@empty')->where('id', '[0-9]+');
Route::resource('slots', 'SlotController');

// SPA
Route::get('/{any}', 'SinglePageController@index')->where('any', '.*');
