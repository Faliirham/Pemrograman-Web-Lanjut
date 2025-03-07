<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function articles ($articleid){
        return 'Halaman Artikel dengan ID ' .$articleid;
    }
}
