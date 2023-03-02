<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PrincipalController;
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

Route::get('/', [PrincipalController::class, 'index'])->name('home');
Route::get('/logout/{uniqueId}', [PrincipalController::class, 'logout'])->name('logout');

Route::post('auth/connection', [PrincipalController::class, 'loginUser'])->name('auth.connection');
Route::post('auth/connection_register', [PrincipalController::class, 'store'])->name('auth.conn_reg');
Route::post('/insertChat', [ChatController::class, 'insertChat'])->name('insert-chat');
Route::post('/getChat', [ChatController::class, 'getChat'])->name('get-chat');
Route::post('/search', [ChatController::class, 'search'])->name('search');


require __DIR__ . '/auth.php';
