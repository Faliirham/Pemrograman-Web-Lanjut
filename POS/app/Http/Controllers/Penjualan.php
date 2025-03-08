<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Penjualan extends Controller
{
    public function DataPenjualan (){
        return view ('penjualan');
    }
}
