<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\BarangModel;
use App\Models\PenjualanModel;
use App\Models\UserModel;
use App\Models\DetailModel;
use App\Models\StokModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables;



class PenjualanController extends Controller
{
    public function print($id)
    {
        $penjualan = PenjualanModel::with('user')->find($id);
        $user = UserModel::all();
        $barang = BarangModel::all();
        $detail = DetailModel::all();

        $pdf = PDF::loadView('penjualan.print', compact('penjualan', 'user', 'barang', 'detail'));

        // Tampilkan PDF sebagai preview sebelum di-download
        return $pdf->stream('struk_penjualan_' . $id . '.pdf');
    }

    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Transaksi Penjualan',
            'list' => ['Home', 'Penjualan']
        ];
        $page = (object)[
            'title' => 'Daftar transaksi penjualan yang terdaftar dalam sistem'
        ];
        $activeMenu = 'penjualan';   //set menu yg sdg aktif

        $barang = BarangModel::all();
        $user = UserModel::all();
        $penjualan = PenjualanModel::all();
        return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'user' => $user, 'penjualan' => $penjualan, 'activeMenu' => $activeMenu]);
    }
    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $penjualans = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->with('user');

        // Praktikum 4 JS 7 - Filter data user berdasarkan level_id
        if ($request->user_id) {
            $penjualans->where('user_id', $request->user_id);
        }

        return DataTables::of($penjualans)
            ->addIndexColumn() // Menambahkan kolom index / no urut (default nmaa kolom: DT_RowINdex)
            ->addColumn('aksi', function ($penjualan) {
                $btn = '<a href="' . url('/penjualan/' . $penjualan->penjualan_id) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>  &nbsp;';
                //$btn .= '<a href="' . url('/penjualan/' . $penjualan->penjualan_id . '/edit') . '" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a>  &nbsp;';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/penjualan/' . $penjualan->penjualan_id) . '">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');"><i class="fas fa-trash"></i></button></form>';
                return $btn;
            })

            ->rawColumns(['aksi'])
            ->make(true);
    }


    public function create()
{
    $breadcrumb = (object)[
        'title' => 'Tambah Transaksi Penjualan',
        'list' => ['Home', 'Transaksi Penjualan', 'Tambah']
    ];
    $page = (object)[
        'title' => 'Tambah transaksi penjualan'
    ];
    $user = UserModel::all();
    $barang = BarangModel::all(); // ambil data level untuk ditampilkan di form
    $activeMenu = 'penjualan'; //set menu yang sedang aktif

    // Generate Kode Penjualan Otomatis dengan awalan "PJF" dan nomor urut
    $nomorUrut = PenjualanModel::count() + 3; // Mendapatkan nomor urut berikutnya
    $kodePenjualan = 'PJF' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT); // Contoh: PJF0001

    return view('penjualan.create', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'user' => $user,
        'barang' => $barang,
        'kodePenjualan' => $kodePenjualan, // Kirim kode penjualan ke view
        'activeMenu' => $activeMenu
    ]);
}

    
public function store(Request $request)
{
    $request->validate([
        'penjualan_kode' => 'required|string|min:5|unique:t_penjualan,penjualan_kode',
        'user_id' => 'required|integer',
        'pembeli' => 'required|string|max:100',
        'penjualan_tanggal' => 'required|date',
        'barang_id' => 'required|array',
        'barang_id.*' => 'integer',
        'jumlah' => 'required|array',
        'jumlah.*' => 'integer|min:0'
    ]);

    // Simpan data transaksi
    $penjualan = PenjualanModel::create([
        'penjualan_kode' => $request->penjualan_kode,
        'user_id' => $request->user_id,
        'pembeli' => $request->pembeli,
        'penjualan_tanggal' => $request->penjualan_tanggal,
    ]);

    // Loop untuk memproses setiap barang yang dibeli
    for ($i = 0; $i < count($request->barang_id); $i++) {
        // Abaikan jika jumlah barang 0 atau kurang dari 0
        if ($request->jumlah[$i] <= 0) {
            continue;
        }

        $barang = BarangModel::findOrFail($request->barang_id[$i]);
        $jumlahBarang = $request->jumlah[$i];

        // Periksa apakah stok barang ada
        $stokBarang = StokModel::where('barang_id', $barang->barang_id)->latest()->first();
        if (!$stokBarang) {
            return redirect('/penjualan')->with('error', 'Stok barang ' . $barang->barang_nama . ' tidak ditemukan');
        }

        // Periksa apakah jumlah barang yang dibeli melebihi stok yang tersedia
        if ($stokBarang->stok_jumlah < $jumlahBarang) {
            return redirect('/penjualan')->with('error', 'Stok barang ' . $barang->barang_nama . ' kurang dari jumlah yang dibeli');
        }

        // Hitung harga berdasarkan harga jual barang dan jumlah
        $hargaBarang = $barang->harga_jual * $jumlahBarang;

        // Simpan detail penjualan
        $detail = DetailModel::create([
            'penjualan_kode' => $request->penjualan_kode,
            'penjualan_id' => $penjualan->penjualan_id, // ID penjualan tetap sama untuk semua detail
            'barang_id' => $barang->barang_id,
            'harga' => $hargaBarang,
            'jumlah' => $jumlahBarang
        ]);

        // Kurangi stok barang
        $newStok = $stokBarang->stok_jumlah - $jumlahBarang; // Kurangi stok sesuai dengan jumlah barang yang dibeli
        $stokBarang->update(['stok_jumlah' => $newStok]);
    }

    return redirect('/penjualan')->with('success', 'Data transaksi berhasil disimpan');
}






    public function show(string $id)
    {
        $penjualan = PenjualanModel::with('user')->find($id);
        $user = UserModel::all();
        $barang = BarangModel::all();
        $detail = DetailModel::all();

        $breadcrumb = (object)[
            'title' => 'Detail Transaksi',
            'list' => ['Home', 'Transaksi', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail Transaksi'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'penjualan' => $penjualan, 'user' => $user, 'barang' => $barang, 'detail' => $detail, 'activeMenu' => $activeMenu]);
    }
    public function edit(string $id)
{
    $penjualan = PenjualanModel::with('user')->findOrFail($id); // Ambil data transaksi spesifik
    $user = UserModel::all();
    $barang = BarangModel::all();
    $detail = DetailModel::all();

    $breadcrumb = (object)[
        'title' => 'Edit Penjualan',
        'list' => ['Home', 'Penjualan', 'Edit']
    ];

    $page = (object)[
        'title' => 'Edit Penjualan'
    ];

    $activeMenu = 'penjualan';

    return view('penjualan.edit', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'penjualan' => $penjualan,
        'user' => $user,
        'barang' => $barang,
        'detail' => $detail,
        'activeMenu' => $activeMenu
    ]);
}




    public function update(Request $request, string $id)
    {
        $request->validate([
            'penjualan_kode'        => 'nullable|string|min:5|unique:t_penjualan,penjualan_kode,' . $id . ',penjualan_id',
            'user_id'               => 'required|integer',
            'pembeli'               => 'required|string|max:100',
            'penjualan_tanggal'     => 'required|date',
        ]);
        PenjualanModel::find($id)->update([
            'penjualan_kode'        => $request->penjualan_kode ? ($request->penjualan_kode) : PenjualanModel::find($id)->penjualan_kode,
            'user_id'               => $request->user_id,
            'pembeli'               => $request->pembeli,
            'penjualan_tanggal'     => $request->penjualan_tanggal
        ]);
        return redirect('/penjualan')->with('success', 'Data penjualan berhasil diubah');
    }
    public function destroy(string $id)
{
    $penjualan = PenjualanModel::find($id);

    if (!$penjualan) {
        return redirect('/penjualan')->with('error', 'Data transaksi penjualan tidak ditemukan');
    }

    try {
        // Hapus terlebih dahulu semua detail transaksi yang terkait
        $penjualan->detail()->delete();

        // Setelah itu baru hapus transaksi penjualan itu sendiri
        $penjualan->delete();

        return redirect('/penjualan')->with('success', 'Data transaksi penjualan berhasil dihapus');
    } catch (\Illuminate\Database\QueryException $e) {
        return redirect('/penjualan')->with('error', 'Data transaksi penjualan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
    }
}

}