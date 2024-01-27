<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

// User Register and Login
Route::get('register', [AuthController::class, 'getRegister'])->name('getRegister');
Route::post('postRegister', [AuthController::class, 'postRegister'])->name('postRegister');
Route::get('login', [AuthController::class, 'getLogin'])->name('login');
Route::post('postLogin', [AuthController::class, 'postLogin'])->name('postLogin');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Tasks Resource
Route::resource('tasks', TaskController::class);
Route::post('tasks/getTaskbyStatus', [TaskController::class, 'getTaskbyStatus'])->name('getTaskbyStatus');
Route::post('tasks/updateStatus', [TaskController::class, 'updateStatus'])->name('updateStatus'); // Todo, On Progress, Done
Route::post('tasks/updateState', [TaskController::class, 'updateState'])->name('updateState'); // Draft or Published



