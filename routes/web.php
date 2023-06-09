<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;

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
    return view('welcome');
});

//route resource
Route::get('/auth', [AuthController::class, 'index'])->name('auth.index');
Route::post('/auth-login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::resource('/articles', \App\Http\Controllers\ArticleController::class);
