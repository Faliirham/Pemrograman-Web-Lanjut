<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index (){
        return 'Selamat Datang';
    }
    public function about (){
        return 'Fali Irham Maulana <br>
        2341720121';
    }
    public function articles ($articleid){
        return 'Halaman Artikel dengan ID ' .$articleid;
    }
}
