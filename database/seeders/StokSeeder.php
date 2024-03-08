<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Mendapatkan semua barang yang telah ada
         $barangIds = DB::table('m_barang')->pluck('barang_id')->toArray();
        
         // Mendapatkan jumlah barang yang ada
         $jumlahBarang = count($barangIds);
         
         // Menentukan jumlah data yang ingin dimasukkan
         $jumlahData = 10;
         
         // Membuat data stok secara acak untuk setiap barang
         $data = [];
         for ($i = 0; $i < $jumlahData; $i++) {
             $barangId = $barangIds[rand(0, $jumlahBarang - 1)];
             $stokJumlah = rand(10, 100);
             
             // Menentukan tanggal secara acak dalam rentang waktu satu bulan
             $stokTanggal = now()->subDays(rand(0, 30));
             
             $data[] = [
                 'barang_id' => $barangId,
                 'user_id' => 1, // ID pengguna dapat disesuaikan dengan kebutuhan
                 'stok_tanggal' => $stokTanggal,
                 'stok_jumlah' => $stokJumlah,
             ];
            }

            DB::table('t_stok')->insert($data);

    }
}
