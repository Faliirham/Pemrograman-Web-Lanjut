<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Products extends Controller
{
    public function foodBeverage (){
        return view('foodBeverage');
    }
    public function beautyHealth (){
        return view ('beautyHealth');
    }
    public function homeCare (){
        return view ('homeCare');
    }
    public function babyKid (){
        return view ('babyKid');
    }
}
