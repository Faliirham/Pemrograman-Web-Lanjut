<?php

use App\Http\Controllers\PageController;
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

//Soal No 6 Praltikum 2
Route::get('/', [PageController::class,'index']);
Route::get('/about', [PageController::class, 'about']);
Route::get('/articles/{id}', [PageController::class, 'articles']);

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