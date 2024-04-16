<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use App\DataTables\KategoriDataTable;
use App\Http\Requests\StorePostRequest;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori'],
        ];
        $page = (object) [
            'title' => 'Daftar Kategori yang terdaftar dalam sistem',
        ];

        $activeMenu = 'kategori';

        $kategori = KategoriModel::all();

        return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kategori' => $kategori]);
    }

    public function list(Request $request)
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');
        if ($request->kategori_id) {
            $kategori->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($kategori)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                $btn = '<a href="' . url('/kategori/' . $kategori->kategori_id) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a> &nbsp';
                $btn .= '<a href="' . url('/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a> &nbsp';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori/' . $kategori->kategori_id) . '">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');"><i class="fas fa-trash"></i></button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];
        $page = (object)[
            'title' => 'Tambah Kategori Baru'
        ];

        $kategori = KategoriModel::all(); //ambil data untuk ditampilkan di form
        $activeMenu = 'kategori';
        return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_kode' => 'bail|required|string|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:100',
        ]);
        KategoriModel::create([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);
        return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
    }

    public function show(string $id)
    {
        $kategori = KategoriModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Kategori',
            'list' => ['Home', 'Kategori', 'Detail']
        ];
        $page = (object)[
            'title' => 'Detail Kategori'
        ];

        $activeMenu = 'kategori';

        return view('kategori.show', ['kategori' => $kategori, 'breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function edit($id)
    {
        $kategori = KategoriModel::find($id);
        // $kategori = LevelModel::all();

        $breadcrumb = (object)[
            'title' => 'Edit Kategori',
            'list' => ['Home', 'Kategori', 'Edit']
        ];
        $page = (object)[
            'title' => 'Edit Kategori'
        ];

        $activeMenu = 'kategori';

        return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kategori' => $kategori]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_kode' => 'bail|required|string|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
            'kategori_nama' => 'required|string|max:100',
        ]);

        KategoriModel::find($id)->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = KategoriModel::find($id);
        if (!$check) {
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        }

        try {
            KategoriModel::destroy($id);

            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
        } catch (\illuminate\Database\QueryException $e) {
            return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
//     public function index(KategoriDataTable $dataTable)
//     {
//         return $dataTable->render('kategori.index');
//     }

//     public function create()
//     {
//         return view('kategori.create');
//     }

//     // public function store(Request $request)
//     // {
//     //     KategoriModel::create([
//     //         'kategori_kode' => $request->kodeKategori,
//     //         'kategori_nama' => $request->namaKategori,
//     //     ]);

//     //     return redirect('/kategori');
//     // }

//     public function store(StorePostRequest $request):RedirectResponse
//     {
//         // $validate = $request->validate(
//         //     [
//         //         'kodeKategori' => 'bail|required|max:5|unique:m_kategori,kategori_kode',
//         //         'namaKategori' => 'required'
//         //     ]
//         // );
//         $validated = $request->validated();

//         $validated = $request->safe()->only(['kategori_kode', 'kategori_nama']);
//         $validated = $request->safe()->except(['kategori_kode', 'kategori_nama']);

//         return redirect('/kategori');
//     }

//     public function delete($id)
// {
//     $kategori = KategoriModel::find($id);
//     if (!$kategori) {
//         abort(404);
//     }
    
//     $kategori->delete();
    
//     return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
// }

// public function edit($id)
//     {
//         $kategori = KategoriModel::find($id);
//         return view('kategori.edit', compact('kategori'));
//     }

//     public function update(Request $request, $id)
//     {
//         $kategori = KategoriModel::find($id);
//         $kategori->kategori_kode = $request->kategori_kode;
//         $kategori->kategori_nama = $request->kategori_nama;
//         $kategori->save();

//         return redirect('/kategori')->with('success', 'Kategori berhasil diupdate');
//     }

}
