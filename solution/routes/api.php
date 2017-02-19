<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('leads', 'ProspectController@store');
Route::post('leads/{id}', 'ProspectController@edit');

Route::get('regions', 'RegionController@listRegions');
Route::get('regions/{id}/unities', 'RegionController@listUnities');