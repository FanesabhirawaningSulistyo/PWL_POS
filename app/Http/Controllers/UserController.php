<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Menyiapkan data untuk dimasukkan ke dalam tabel m_user
       $data= [
        'nama' => 'Pelanggan Pertama',
       ];
       UserModel::where('username','customer-1')->update($data);

        // Mengambil semua data pengguna dari tabel m_user
        $users = UserModel::all();

        // Mengembalikan view 'user' dengan data pengguna
        return view('user', ['data' => $users]);
    }
}
