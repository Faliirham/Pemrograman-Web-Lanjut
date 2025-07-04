<?php

use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\KategoriController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', \App\Http\Controllers\Api\RegisterController::class)
    ->name('register');
Route::post('/login', \App\Http\Controllers\Api\LoginController::class)->name('login');
Route::middleware(['auth:api'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/logout', \App\Http\Controllers\Api\LogoutController::class)
    ->name('logout');

Route::get('levels', [UserController::class, 'index']);
Route::post('levels', [UserController::class, 'store']);
Route::get('levels/{level}', [UserController::class, 'show']);
Route::put('levels/{level}', [UserController::class, 'update']);
Route::delete('levels/{level}', [UserController::class, 'destroy']);

Route::get('users', [UserController::class, 'index']);
Route::post('users', [UserController::class, 'store']);
Route::get('users/{user}', [UserController::class, 'show']);
Route::put('users/{user}', [UserController::class, 'update']);
Route::delete('users/{level}', [UserController::class, 'destroy']);

Route::get('kategoris', [KategoriController::class, 'index']);
Route::post('kategoris', [KategoriController::class, 'store']);
Route::get('kategoris/{kategori}', [KategoriController::class, 'show']);
Route::put('kategoris/{kategori}', [KategoriController::class, 'update']);
Route::delete('kategoris/{kategori}', [KategoriController::class, 'destroy']);

Route::get('barangs', [BarangController::class, 'index']);
Route::post('barangs', [BarangController::class, 'store']);
Route::get('barangs/{barang}', [BarangController::class, 'show']);
Route::put('barangs/{barang}', [BarangController::class, 'update']);
Route::delete('barangs/{barang}', [BarangController::class, 'destroy']);

Route::post('/register1', \App\Http\Controllers\Api\RegisterController::class)
    ->name('register1');