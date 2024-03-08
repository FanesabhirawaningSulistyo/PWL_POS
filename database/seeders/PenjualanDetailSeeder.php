<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan jumlah data yang ingin dimasukkan
        $jumlahData = 30;

        // Mendapatkan semua barang yang telah ada
        $barangIds = DB::table('m_barang')->pluck('barang_id')->toArray();

        // Mendapatkan jumlah barang yang ada
        $jumlahBarang = count($barangIds);

        // Mendapatkan semua transaksi penjualan yang telah ada
        $penjualanIds = DB::table('t_penjualan')->pluck('penjualan_id')->toArray();

        // Membuat data detail transaksi penjualan
        $data = [];
        for ($i = 0; $i < $jumlahData; $i++) {
            $penjualanId = $penjualanIds[rand(0, count($penjualanIds) - 1)]; // Memilih transaksi penjualan secara acak
            $barangId = $barangIds[rand(0, $jumlahBarang - 1)]; // Memilih barang secara acak
            $harga = rand(1000, 50000); // Harga barang secara acak
            $jumlah = rand(1, 10); // Jumlah barang secara acak

            $data[] = [
                'detail_id' => $i + 1, // Penambahan 1 karena detail_id dimulai dari 1
                'penjualan_id' => $penjualanId,
                'barang_id' => $barangId,
                'harga' => $harga,
                'jumlah' => $jumlah,
            ];
        }

        DB::table('t_penjualan_detail')->insert($data);
    }
}
