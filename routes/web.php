<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Auth\UserAuthControlleur;

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

Route::get('/', [UserAuthControlleur::class, 'index'])->name('home');

Route::prefix('auth')->group(function () {
    Route::get('/userLogin', [UserAuthControlleur::class, 'login'])->name('userLogin');
    Route::get('/userRegister', [UserAuthControlleur::class, 'register'])->name('userRegister');
    Route::get('/logout/{uniqueId}', [UserAuthControlleur::class, 'destroy'])->name('logout');

    Route::post('/userLogin', [UserAuthControlleur::class, 'userLogin'])->name('userLogin');
    Route::post('/userRegister', [UserAuthControlleur::class, 'userRegister'])->name('userRegister');
});

Route::get('/users', [ChatController::class, 'users'])->name('users');
Route::post('/insertChat', [ChatController::class, 'insertChat'])->name('insertChat');
Route::post('/getChat', [ChatController::class, 'getChat'])->name('getChat');
Route::post('/search', [ChatController::class, 'search'])->name('search');


require __DIR__ . '/auth.php';
