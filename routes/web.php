<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {

    if (auth()->check()) {
        return view('dashboard');
    } else {
        return view('auth.login'); // Show login page if not authenticated
    }
});


Auth::routes();


Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    Route::get('/tasks', [App\Http\Controllers\TaskController::class, 'index'])->name('task');
    Route::get('/tasks/add', [App\Http\Controllers\TaskController::class, 'add'])->name('task.add');

});
