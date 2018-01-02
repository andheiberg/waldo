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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->namespace('API')->group(function () {
    Route::resource('branches', 'BranchesController', ['only' => ['index', 'show']]);
    Route::resource('commits', 'CommitsController', ['only' => ['index', 'show']]);
    Route::resource('screenshots', 'ScreenshotsController', ['only' => ['index', 'show', 'store']]);
});
