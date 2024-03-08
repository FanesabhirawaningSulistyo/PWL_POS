<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jumlahData = 10;
        
        // Membuat data transaksi penjualan
        $data = [];
        for ($i = 0; $i < $jumlahData; $i++) {
            $penjualanKode = 'PJ' . str_pad($i + 1, 5, '0', STR_PAD_LEFT); // Format kode penjualan
            $penjualanTanggal = now()->subDays(rand(0, 30)); // Menentukan tanggal secara acak dalam rentang waktu satu bulan
            
            $data[] = [
                'user_id' => 1, // ID pengguna dapat disesuaikan dengan kebutuhan
                'pembeli' => 'Pelanggan ' . ($i + 1), // Nama pembeli
                'penjualan_kode' => $penjualanKode,
                'penjualan_tanggal' => $penjualanTanggal,
            ];
        }

        DB::table('t_penjualan')->insert($data);
    }
}
