<?php

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

//Soal No 2 Praltikum 1
Route::get('/hello', function () {
    return 'Hello World';
});

//Soal No 4 Praltikum 1
Route::get('/world', function () {
    return 'World';
});

//Soal No 6 Praltikum 1
Route::get('/', function () {
    return 'Selamat Datang';
});

//Soal No 7 Praltikum 1
Route::get('/about', function () {
    return '2341720121 <br>
            Fali irham Maulana';
});

//Soal No 8 Praltikum 1
Route::get('/user/{name}', function ($name) {
    return 'Nama Saya '.$name;
});

//Soal No 11 Praltikum 1
Route::get('/posts/{posts}/coments/{coments}', function ($postId, $comentId) {
    return 'Pos ke-' .$postId. "Komentar ke-" .$comentId ;
});

//Soal no 13 Praktikum 1
Route::get('/articles/{id}', function ($articleid){
    return 'Halaman Artikel dengan ID ' .$articleid;
});

//Soal no 14 & 17Praktikum 1
Route::get('/user/{name?}', function ($name = 'Jhon'){
    return 'Nama saya ' .$name;
});