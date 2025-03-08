<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class User extends Controller
{
    public function getUser ($id , $name){
        return 'Nama Pengguna '.$name.'<br> memiliki Id '. $id;
    }
}
