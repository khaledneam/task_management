<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([

    'middleware' => 'api',


], function ($router) {

    Route::post('task/store', [\App\Http\Controllers\TaskController::class,'store']);
    Route::get('task/users', [UserController::class, 'getUsersToAssignTask']);
    Route::get('tasks', [TaskController::class, 'indexVue']);
    Route::delete('task/destroy/{id}', [TaskController::class, 'destroy']);
    Route::put('/task/update/{id}', [TaskController::class, 'update']);



});
