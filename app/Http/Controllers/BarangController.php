<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\StokModel;
use App\Models\UserModel;
use App\Http\Controllers\Controller;

class BarangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['home', 'Barang']
        ];

        $page = (object) [
            'title' => 'Daftar barang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'barang';

        $kategori = KategoriModel::all();

        return view('barang.index', compact('breadcrumb', 'page', 'activeMenu', 'kategori'));
    }

    public function list(Request $request)
    {
        $barangs = BarangModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')->with('kategori');

        if ($request->kategori_id) {
            $barangs->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($barangs)
            ->addIndexColumn()
            ->addColumn('aksi', function ($barang) {
                $btn = '<a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btn-sm mr-2"><i class="fas fa-eye"></i></a>';
                $btn .= '<a href="' . url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm mr-2"><i class="fas fa-pencil-alt"></i></a>';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang/' . $barang->barang_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');"><i class="fas fa-trash"></i></button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah barang baru'
        ];

        $kategori = KategoriModel::all();
        $user = UserModel::all();
        $activeMenu = 'barang';

        return view('barang.create', compact('breadcrumb', 'page', 'kategori', 'activeMenu', 'user'));
    }

    public function store(Request $request)
{
    $request->validate([
        'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode',
        'barang_nama' => 'required|string|max:100',
        'harga_beli' => 'required|integer',
        'harga_jual' => 'required|integer|gt:harga_beli', // Memastikan harga jual lebih besar dari harga beli
        'kategori_id' => 'required|integer',
        'image'        => 'required|file|image|max:1000',
        'stok_jumlah' => 'required|integer',
        'user_id' => 'required|integer'
    ], [
        'harga_jual.gt' => 'Harga jual harus lebih besar dari harga beli.'
    ]);

    $namaFile = 'IMG' . time() . '-' . $request->image->getClientOriginalName();
    $path = $request->image->storeAs('public/barang', $namaFile);

    BarangModel::create([
        'barang_kode' => $request->barang_kode,
        'barang_nama' => $request->barang_nama,
        'harga_beli' => $request->harga_beli,
        'harga_jual' => $request->harga_jual,
        'kategori_id' => $request->kategori_id,
        'image'          => $namaFile
    ]);

    StokModel::create([
        'stok_tanggal' => now()->format('Y-m-d'),
        'stok_jumlah' => $request->stok_jumlah,
        'barang_id' => BarangModel::latest()->first()->barang_id,
        'user_id' => $request->user_id
    ]);

    return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
}


    public function show(string $id)
    {
        $barang = BarangModel::with('kategori')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail barang'
        ];

        $activeMenu = 'barang';

        return view('barang.show', compact('breadcrumb', 'page', 'barang', 'activeMenu'));
    }

    public function edit(string $id)
    {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Barang',
            'list' => ['Home', 'Barang', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit barang'
        ];

        $activeMenu = 'barang';

        return view('barang.edit', compact('breadcrumb', 'page', 'barang', 'kategori', 'activeMenu'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode,' .$id. ',barang_id',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer|gt:harga_beli',
            'kategori_id' => 'required|integer',
            'image'        => 'nullable|file|image|max:1000',
        ]
        , [
            'harga_jual.gt' => 'Harga jual harus lebih besar dari harga beli.'
        ]);

        if ($request->image) {
            $namaFile = 'IMG' . time() . '-' . $request->image->getClientOriginalName();
            $path = $request->image->storeAs('public/barang', $namaFile);
        }

        BarangModel::find($id)->update([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'kategori_id' => $request->kategori_id,
            'image'       => $request->image ? $namaFile : basename(BarangModel::find($id)->image)
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }

    public function destroy(string $id)
    {
        $checkBarang = BarangModel::find($id);

        if (!$checkBarang) {
            return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
        }

        try {
            StokModel::where('barang_id', $id)->delete();
            BarangModel::destroy($id);
            return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terikat dengan data ini');
        }
    }
}