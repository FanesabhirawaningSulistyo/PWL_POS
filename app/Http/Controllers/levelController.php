<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\LevelModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar User',
            'list' => ['Home', 'Level User']
        ];
        $page = (object)[
            'title' => 'Daftar level user yang terdaftar dalam sistem'
        ];
        $activeMenu = 'level';   //set menu yg sdg aktif

        $level = LevelModel::all();
        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');

        // Praktikum 4 JS 7 - Filter data user berdasarkan level_id
        if ($request->level_id) {
            $levels->where('level_id', $request->level_id);
        }

        return DataTables::of($levels)
            ->addIndexColumn() // Menambahkan kolom index / no urut (default nmaa kolom: DT_RowINdex)
            ->addColumn('aksi', function ($level) {
                $btn = '<a href="' . url('/level/' . $level->level_id) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>  &nbsp;';
                $btn .= '<a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a>  &nbsp;';                
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/level/' . $level->level_id) . '">'
                . csrf_field() . method_field('DELETE') .
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');"><i class="fas fa-trash"></i></button></form>';
                return $btn;
            })

            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Level User',
            'list' => ['Home', 'Level User', 'Tambah']
        ];
        $page = (object)[
            'title' => 'Tambah level user baru'
        ];

        $level = LevelModel::all(); // ambil data level untuk ditampilkan di form
        $activeMenu = 'level'; //set menu yang sedang aktif

        return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'level_kode'   => 'required|string|min:3|unique:m_level,level_kode',
            'level_nama'       => 'required|string|max:100',
        ]);
        LevelModel::create([
            'level_kode'    => $request->level_kode,
            'level_nama'        => $request->level_nama
        ]);

        return redirect('/level')->with('success', 'Data level user berhasil disimpan');
    }
    public function show(string $id)
    {
        $level = LevelModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Level User',
            'list' => ['Home', 'Level User', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail Level User'
        ];

        $activeMenu = 'level';

        return view('level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    public function edit(string $id)
    {
        $level = LevelModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Edit Level User',
            'list' => ['Home', 'Level User', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit Level User'
        ];

        $activeMenu = 'level';

        return view('level.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'level_kode'   => 'required|string|min:3|unique:m_level,level_kode,' . $id . ',level_id',
            'level_nama'   => 'required|string|max:100'
        ]);
        LevelModel::find($id)->update([
            'level_kode'    => $request->level_kode,
            'level_nama'    => $request->level_nama,
        ]);

        return redirect('/level')->with('success', 'Data level user berhasil diubah');
    }
    public function destroy(string $id)
    {
        $check = LevelModel::find($id);
        if (!$check) {
            return redirect('/level')->with('error', 'Data level user tidak ditemukan');
        }
        try {
            LevelModel::destroy($id);
            return redirect('/level')->with('success', 'Data level user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/level')->with('error', 'Data level user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}