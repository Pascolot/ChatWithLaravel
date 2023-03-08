<?php

use App\Http\Controllers\Auth\UserAuthControlleur;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

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

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [UserAuthControlleur::class, 'dashboard'])->name('dashboard');
    Route::get('/chat/{uniqueId}', [ChatController::class, 'chat'])->name('chat');
    Route::get('/sousChat', [ChatController::class, 'sousChat'])->name('sousChat');
});
