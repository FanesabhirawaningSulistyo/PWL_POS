<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Data yang akan dimasukkan ke dalam tabel m_user
        // $data = [
        //     'level_id' => 2,
        //     'username' => 'manager_tiga',
        //     'nama' => 'Manager 3',
        //     'password' => Hash::make('12345') // Mengenkripsi password sebelum disimpan
        // ];

        // // Menyimpan data ke dalam tabel m_user menggunakan model UserModel
        // UserModel::create($data);
        
        $users = UserModel::findOr(20,['username','nama'], function(){
        abort(404);
    });
        return view('user', ['data' => $users]);
    }
}
