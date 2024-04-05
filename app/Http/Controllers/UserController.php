<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\LevelModel;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];
        $page = (object)[
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];
        $activeMenu = 'user';   //set menu yg sdg aktif

        $level = LevelModel::all();
        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'level'=>$level, 'activeMenu' => $activeMenu]);  
    }

    public function tambah()
    {
        return view('user_tambah');
    }

    public function tambah_simpan(StorePostRequest $request)
    {
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
            'level_id' => $request->level_id,
        ]);

        $validated = $request->validate();
            $validated = $request->safe()->only(['level_id','username', 'nama', 'password' ]);
            $validated=$request->safe()->except(['level_id','username', 'nama', 'password' ]);
        
        return redirect('/user');
    }

    public function ubah($id)
    {
        $user = UserModel::find($id);
        return view('user_ubah', ['data' => $user]);
    }

    public function ubah_simpan($id, Request $request)
    {
        $user = UserModel::find($id);

        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make($request->password); // Remove the quotation marks
        $user->level_id = $request->level_id;
        $user->save();

        return redirect('/user');
    }

    public function hapus($id)
    {
        $user = UserModel::find($id);
        $user->delete();

        return redirect('/user');
    }

    public function list(Request $request)
{
    $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
        ->with('level');

    //Filter data berdasarkan level_id
    if ($request->level_id) {
        $users->where('level_id', $request->level_id);
    }

    return DataTables::of($users)
        ->addIndexColumn()
        ->addColumn('aksi', function ($user) {
            $btn = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a> ';
            $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a> ';
            $btn .= '<form class="d-inline-block" method="POST" action="'. url('/user/'.$user->user_id).'">'
                . csrf_field() . method_field('DELETE')
                . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');"><i class="fas fa-trash"></i></button></form>';
            return $btn;

        })
        ->rawColumns(['aksi'])
        ->make(true);
}

//menampilkan halaman form tambah user
public function create()
{
    $breadcrumb = (object) [
        'title' => 'Tambah User',
        'list' => ['Home', 'User', 'Tambah']
    ];
     $page = (object) [
        'title' => 'Tambah user baru'
     ];

     $level = LevelModel::all();
    $activeMenu = 'user';   //set menu yg sdg aktif
    
    return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page,  'level' => $level, 'activeMenu' => $activeMenu]);
}

//menyimpan data user baru
public function store(Request $request)
{
    $request->validate([
        'username' => 'required|string|min:3|unique:m_user,username',
        'nama' => 'required|string|max:100',
        'password' => 'required|string|min:5',
        'level_id' => 'required|integer'
    ]);

    UserModel::create([
        'username' => $request->username,
        'nama' => $request->nama,
        'password' => bcrypt($request->password),
        'level_id' => $request->level_id,
    ]);

    return redirect('/user')->with('success', 'Data user berhasil disimpan');

}

//menampilkan detail user
public function show(string $id)
{
    $user = UserModel::with('level')->find($id);
    $breadcrumb = (object) [
        'title' => 'Detail User',
        'list' => ['Home', 'User', 'Detail']
    ];
    $page = (object) [
        'title' => 'Detail user'
    ];
    $activeMenu = 'user';   //set menu yg sdg aktif

    return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
}

public function edit(string $id)
{
    $user = UserModel::find($id);
    $level = LevelModel::all();
    $breadcrumb = (object)[
        'title' => 'Edit User',
        'list' => ['Home', 'User', 'Edit']
    ];
    $page = (object)['title' => 'Edit user 1'];
    $activeMenu = 'user'; // set menu yang sedang aktif

    return view('user.edit', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'user' => $user,
        'level' => $level,
        'activeMenu' => $activeMenu
    ]);
}

public function update(Request $request, string $id)
{
    $request->validate([
        'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
        'nama' => 'required|string|max:100',
        'password' => 'nullable|min:5',
        'level_id' => 'required|integer'
    ]);

    $user = UserModel::find($id);
    $user->username = $request->username;
    $user->nama = $request->nama;
    $user->password = $request->password ? bcrypt($request->password) : $user->password;
    $user->level_id = $request->level_id;
    $user->save();

    return redirect('/user')->with("success", "Data user berhasil diubah");
}

public function destroy(string $id)
{
    $user = UserModel::find($id);
    if (!$user) {
        return redirect('/user')->with('error', 'Data user tidak ditemukan');
    }

    try {
        $user->delete(); // Hapus data user
        return redirect('/user')->with('success', 'Data user berhasil dihapus');
    } catch (QueryException $e) { // Tangkap exception QueryException
        return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
    }
}

}