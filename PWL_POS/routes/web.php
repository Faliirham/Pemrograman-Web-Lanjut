<?php

use App\Http\Controllers\Home;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Penjualan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products;
use App\Http\Controllers\User;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;

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
    return view('welcome');
});

Route::get('/home', [HomeController::class, 'index']);

Route::prefix('category')->group(function () {
    Route::get('/foodBeverage', [Products::class, 'foodBeverage']);
    Route::get('/beautyHealth', [Products::class, 'beautyHealth']);
    Route::get('/homeCare', [Products::class, 'homeCare']);
    Route::get('/babyKid', [Products::class, 'babyKid']);
});

Route::get('/user/{id}/name/{name}', [User::class, 'getUser']);

Route::get('/penjualan', [Penjualan::class, 'DataPenjualan']);

Route::get('/level', [LevelController::class, 'index']);

Route::get('/kategori', [KategoriController::class, 'index']);