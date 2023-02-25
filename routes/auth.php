<?php

use App\Http\Controllers\Auth\PrincipalController;
use App\Http\Controllers\ChatController;
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

Route::group(['middleware' => ['authVerification']], function () {
    Route::get('auth/login', [PrincipalController::class, 'login'])->name('auth.login');
    Route::get('auth/register', [PrincipalController::class, 'register'])->name('auth.register');
    Route::get('/dashboard', [PrincipalController::class, 'connection'])->name('dashboard');

    Route::get('/users', [ChatController::class, 'users'])->name('users');
    Route::get('/chat/{id}', [ChatController::class, 'chat'])->name('chat');
    Route::get('/sousChat', [ChatController::class, 'sousChat'])->name('sousChat');
});
