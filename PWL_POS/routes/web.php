<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\Home;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Penjualan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products;
use App\Http\Controllers\User;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;

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

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/list', [UserController::class, 'list']);
    Route::get('/create', [UserController::class, 'create']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/create_ajax', [UserController::class, 'create_ajax']);
    Route::post('/ajax', [UserController::class, 'store_ajax']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::get('/{id}/edit', [UserController::class, 'edit']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});


Route::group(['prefix' => 'level'], function () {
    Route::get('/', [LevelController::class, 'index']); // Halaman awal level
    Route::post('/list', [LevelController::class, 'list']); // DataTables JSON
    Route::get('/create', [LevelController::class, 'create']); // Form tambah level
    Route::post('/', [LevelController::class, 'store']); // Simpan level baru
    Route::get('/{id}', [LevelController::class, 'show']); // Detail level
    Route::get('/{id}/edit', [LevelController::class, 'edit']); // Form edit level
    Route::put('/{id}', [LevelController::class, 'update']); // Update level
    Route::delete('/{id}', [LevelController::class, 'destroy']);// Hapus level
});


Route::group(['prefix' => 'kategori'], function () {
    Route::get('/', [KategoriController::class, 'index']); // Halaman awal kategori
    Route::post('/list', [KategoriController::class, 'list']); // DataTables JSON
    Route::get('/create', [KategoriController::class, 'create']); // Form tambah kategori
    Route::post('/', [KategoriController::class, 'store']); // Simpan kategori baru
    Route::get('/{id}', [KategoriController::class, 'show']); // Detail kategori
    Route::get('/{id}/edit', [KategoriController::class, 'edit']); // Form edit kategori
    Route::put('/{id}', [KategoriController::class, 'update']); // Update kategori
    Route::delete('/{id}', [KategoriController::class, 'destroy']); // Hapus kategori
  });
  Route::group(['prefix' => 'supplier'], function () {
    Route::get('/', [SupplierController::class, 'index']); // Halaman awal supplier
    Route::post('/list', [SupplierController::class, 'list']); // DataTables JSON
    Route::get('/create', [SupplierController::class, 'create']); // Form tambah supplier
    Route::post('/', [SupplierController::class, 'store']); // Simpan supplier baru
    Route::get('/{id}', [SupplierController::class, 'show']); // Detail supplier
    Route::get('/{id}/edit', [SupplierController::class, 'edit']); // Form edit supplier
    Route::put('/{id}', [SupplierController::class, 'update']); // Update supplier
    Route::delete('/{id}', [SupplierController::class, 'destroy']); // Hapus supplier
  });


 Route::group(['prefix' => 'barang'], function () {
    Route::get('/', [BarangController::class, 'index']); // Halaman daftar barang
    Route::post('/list', [BarangController::class, 'list']); // DataTables JSON
    Route::get('/create', [BarangController::class, 'create']); // Form tambah barang
    Route::post('/', [BarangController::class, 'store']); // Simpan barang baru
    Route::get('/{id}', [BarangController::class, 'show']); // Detail barang
    Route::get('/{id}/edit', [BarangController::class, 'edit']); // Form edit barang
    Route::put('/{id}', [BarangController::class, 'update']); // Update barang
    Route::delete('/{id}', [BarangController::class, 'destroy']); // Hapus barang
});
// Route::get('/home', [HomeController::class, 'index']);

// Route::prefix('category')->group(function () {
//     Route::get('/foodBeverage', [Products::class, 'foodBeverage']);
//     Route::get('/beautyHealth', [Products::class, 'beautyHealth']);
//     Route::get('/homeCare', [Products::class, 'homeCare']);
//     Route::get('/babyKid', [Products::class, 'babyKid']);
// });

// Route::get('/user/{id}/name/{name}', [User::class, 'getUser']);

// Route::get('/penjualan', [Penjualan::class, 'DataPenjualan']);

// Route::get('/level', [LevelController::class, 'index']);

// Route::get('/kategori', [KategoriController::class, 'index']);

// Route::get('/user', [UserController::class, 'index']);

// Route::get('/user/tambah', [UserController::class, 'tambah']);

// Route::post('user/tambah_simpan', [UserController::class, 'tambah_simpan']);

// Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);

// Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);

// Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);