<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

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

//Soal No 4 Praltikum 2
Route::get('/hello', [WelcomeController::class,'hello']);

//Soal No 4 Praltikum 1
Route::get('/world', function () {
    return 'World';
});

//Soal No 7 Praltikum 2
Route::get('/', [HomeController::class,'index']);

Route::get('/about', [AboutController::class, 'about']);

Route::get('/articles/{id}', [ArticleController::class, 'articles']);

//Soal No 8 Praltikum 1
Route::get('/user/{name}', function ($name) {
    return 'Nama Saya '.$name;
});

//Soal No 11 Praltikum 1
Route::get('/posts/{posts}/coments/{coments}', function ($postId, $comentId) {
    return 'Pos ke-' .$postId. "Komentar ke-" .$comentId ;
});

//Soal no 14 & 17Praktikum 1
Route::get('/user/{name?}', function ($name = 'Jhon'){
    return 'Nama saya ' .$name;
});

//Soal no 9 Praktikum 2 
Route::resource('photos', PhotoController::class);

//Soal no 11 Praktikum 2
Route::resource('photos', PhotoController::class)->only([
    'index', 'show'
]);

Route::resource('photos', PhotoController::class)->except([
    'create', 'store', 'update', 'destroy'
]);