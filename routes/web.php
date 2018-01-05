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

Route::get('check-alive', function () {
    return response('', 204);
});

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::get('settings', ['as' => 'settings', 'uses' => 'SettingsController@index']);
Route::put('settings', ['as' => 'settings.update', 'uses' => 'SettingsController@update']);
Route::delete('settings/delete/all', ['as' => 'settings.delete.all', 'uses' => 'SettingsController@deleteAll']);
Route::delete('settings/delete/old', ['as' => 'settings.delete.old', 'uses' => 'SettingsController@deleteOld']);

Route::resource('branches', 'BranchesController', ['only' => ['show']]);
Route::resource('branches/{branch}/screenshots', 'ScreenshotsController', ['only' => ['show']]);
