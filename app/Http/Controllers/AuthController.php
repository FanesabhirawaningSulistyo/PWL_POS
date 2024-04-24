<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        // kita ambil data user lalu simpan pada variable $user
        $user = Auth::user();

        // kondisi jika usernya ada
        if ($user) {
            // jika level admin
            if ($user->level_id == '1') {
                return redirect()->intended('admin');
            }
            //jika level manager
            else if ($user->level_id == '2') {
                return redirect()->intended('manager');
            }
        }
        return view('login');
    }
    public function proses_login(Request $request)
    {
        // kita buat validasi pd saat tombol login diklik
        // validasi username & password wajib diisi
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // ambil data request username & password saja
        $credential = $request->only('username', 'password');
        // cek jika username & password valid (sesuai) dengan data
        if (Auth::attempt($credential)) {
            // jika berhasil, simpan data user di variabel $user
            $user = Auth::user();
            // cek lg jika level user = admin maka arahkan ke halaman admin
            if ($user->level_id == '1') {
                // dd($user->level_id);
                return redirect()->intended('admin');
            }
            // jika level user biasa maka arahkan ke halaman user (manager)
            else if ($user->level_id == '2') {
                // dd($user->level_id);
                return redirect()->intended('manager');
            }
            // jika belum ada role maka ke halaman /
            return redirect()->intended('/');
        }
        // jika tidak ada user yang valid maka kembalikan ke halaman login
        // kirim pesan error juga kalau login gagal
        return redirect('login')->withInput()
            ->withErrors(['login_gagal' => 'Pastikan kembali username dan password yang dimasukkan sudah benar']);
    }
    public function register()
    {
        return view('register');
    }
    // aksi form register
    public function proses_register(Request $request)
    {
        // buat validasi buat program register
        // validasinya -> semua field wajib diisi
        // validasi username unique atau tidak boleh duplicate usernamenya
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'username' => 'required|unique:m_user',
            'password' => 'required'
        ]);
        // kalau gagal kembali ke halaman register -> munculkan pesan eror
        if ($validator->fails()) {
            return redirect('/register')
                ->withErrors($validator)
                ->withInput();
        }
        // kalau bberhasil isi level & hash password agar secure
        $request['level_id'] = '2';
        $request['password'] = Hash::make($request->password);

        // masukkan semua data pada request ke table user
        UserModel::create($request->all());

        // jika berhasil arahkan ke halaman login
        return redirect()->route('login');
    }
    public function logout(Request $request)
    {
        // logout -> hapus session
        $request->session()->flush();

        // jalankan fungsi logout pada auth
        Auth::logout();

        // kembali ke halaman login
        return Redirect('login');
    }
}