<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class levelController extends Controller
{
    public function index()
    {
        // DB::insert('insert into m_level (level_kode, level_nama, created_at) values (?, ?, ?)', ['CUS', 'Pelanggan', now()]);
        //  return 'Insert data baru berhasil';

        // $row = DB::update('update m_level set level_nama = ?, updated_at = ? where level_kode = ?', ['Customer', now(), 'CUS']);
        // return 'Update data berhasil. Jumlah data yang diupdate: ' . $row . ' baris';


         // Melakukan penghapusan data berdasarkan level_kode
    //      $row = DB::delete('delete from m_level where level_kode = ?', ['CUS']);
        
    //      // Mengembalikan pesan konfirmasi bahwa data berhasil dihapus
    //      return 'Delete data berhasil. Jumlah data yang dihapus: ' . $row . ' baris';
        $data = DB::select('select * from m_level');
        return view('level', ['data' => $data]);
}
}
