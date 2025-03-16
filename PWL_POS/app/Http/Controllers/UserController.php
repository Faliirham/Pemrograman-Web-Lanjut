<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index (){

        // Menambah data dengan Eloquent Model 
        // $data = [
        //   'username' => 'customer-1',
        //   'nama' => 'Pelanggan',
        //   'password' => Hash::make('12345'),
        //   'level_id' => '4',
        // ];

        $data = [
            'nama' => 'Pelanggan Pertama'
        ]; 

        // UserModel::insert($data);
        UserModel::where('username', 'customer-1')->update($data); //update data user

        $user = UserModel::all();
        return view('user', ['data' => $user]);
    }
}
