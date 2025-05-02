<?php

use App\Http\Controllers\AuthController;
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
Route::pattern('id','[0-9]+');

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class,'logout'])->middleware('auth');
Route::get('register', [AuthController::class, 'register']);
  Route::post('postRegister', [AuthController::class, 'postRegister']);

Route::middleware(['auth'])->group(function(){
    Route::get('/', function () {
        return view('welcome');
    });
    
    Route::get('/', [WelcomeController::class, 'index']);

    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/list', [UserController::class, 'list']);
            Route::get('/create', [UserController::class, 'create']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('/create_ajax', [UserController::class, 'create_ajax']);
            Route::post('/ajax', [UserController::class, 'store_ajax']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); 
            Route::get('/{id}/edit', [UserController::class, 'edit']);
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);// tampilan form delete user ajax
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);// hapus data user ajax
            Route::put('/{id}', [UserController::class, 'update']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
        });
    });
    
    
    Route::middleware(['authorize:ADM'])->group(function () {
        Route::group(['prefix' => 'level'], function () {
            Route::get('/', [LevelController::class, 'index']); // Menampilkan halaman awal level
            Route::post('/list', [LevelController::class, 'list']); // Menampilkan data level dalam bentuk JSON untuk DataTables
            Route::get('/create_ajax', [LevelController::class, 'create_ajax']); // Menampilkan halaman form tambah level
            Route::post('/ajax', [LevelController::class, 'store_ajax']); // Menyimpan data level baru
            Route::get('/{id}', [LevelController::class, 'show']); // Menampilkan detail level
            Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // Menampilkan halaman form edit level
            Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']); // Menyimpan perubahan data level
            Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); // Tampilan form delete level AJAX
            Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); // Hapus data level AJAX
            Route::delete('/{id}', [LevelController::class, 'destroy']); // Menghapus data level
            });
        });
    
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        Route::group(['prefix' => 'kategori'], function () {
            Route::get('/', [KategoriController::class, 'index']); // Menampilkan halaman awal kategori
            Route::post('/list', [KategoriController::class, 'list']); // Menampilkan data kategori dalam JSON untuk DataTables
            Route::get('/create_ajax', [KategoriController::class, 'create_ajax']); // Menampilkan form tambah kategori
            Route::post('/ajax', [KategoriController::class, 'store_ajax']); // Menyimpan data kategori baru
            Route::get('/{id}', [KategoriController::class, 'show']); // Menampilkan detail kategori
            Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); // Menampilkan form edit kategori
            Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']); // Menyimpan perubahan data kategori
            Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); // Tampilan form delete kategori AJAX
            Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // Hapus data kategori AJAX
            Route::delete('/{id}', [KategoriController::class, 'destroy']); // Menghapus data kategori
        });
    });

    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        Route::group(['prefix' => 'supplier'], function () {
            Route::get('/', [SupplierController::class, 'index']); // Halaman awal supplier
            Route::post('/list', [SupplierController::class, 'list']); // DataTables JSON
            Route::get('/create_ajax', [SupplierController::class, 'create_ajax']); // Form tambah supplier
            Route::post('/ajax', [SupplierController::class, 'store_ajax']); // Simpan supplier baru
            Route::get('/{id}', [SupplierController::class, 'show']); // Detail supplier
            Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); // Form edit supplier
            Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // Update supplier
            Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); // Konfirmasi hapus supplier AJAX
            Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // Hapus supplier AJAX
            Route::delete('/{id}', [SupplierController::class, 'destroy']); // Hapus supplier
        });
    });
    
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        Route::group(['prefix' => 'barang'], function () {
            Route::get('/', [BarangController::class, 'index']); // Halaman awal barang
            Route::post('/list', [BarangController::class, 'list']); // DataTables JSON
            Route::get('/create_ajax', [BarangController::class, 'create_ajax']); // Form tambah barang
            Route::post('/ajax', [BarangController::class, 'store_ajax']); // Simpan barang baru
            Route::get('/{id}', [BarangController::class, 'show']); // Detail barang
            Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); // Form edit barang
            Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']); // Update barang
            Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // Konfirmasi hapus barang AJAX
            Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // Hapus barang AJAX
            Route::delete('/{id}', [BarangController::class, 'destroy']); // Hapus barang
            Route::get('/import', [BarangController::class, 'import']); // menampilkan halaman form upload excel barang ajax
            Route::post('/import_ajax', [BarangController::class, 'import_ajax']); // menyimpan import excel barang ajax
            Route::get('/export_excel', [BarangController::class,'export_excel']); //export excel
        });
    });
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