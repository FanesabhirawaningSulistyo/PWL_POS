<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PenjualanModel;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function show($id)
    {
        $penjualan = PenjualanModel::with('detail.barang')->findOrFail($id);

        $response = [
            'ID' => $penjualan->penjualan_id,
            'Pembeli' => $penjualan->pembeli,
            'Kode Penjualan' => $penjualan->penjualan_kode,
            'Tanggal Penjualan' => $penjualan->penjualan_tanggal,
            'Barang yang dibeli' => [],
            'Total Belanja' => 0,
        ];

        foreach ($penjualan->detail as $item) {
            $barang = $item->barang;
            $subtotal = $item->harga * $item->jumlah;
            $response['Barang yang dibeli'][] = [
                'Barang' => $barang->barang_nama,
                'Harga' => $item->harga,
                'Jumlah' => $item->jumlah,
                'Subtotal' => $subtotal,
                'Image' => url('storage/posts/' . $barang->image),
            ];
            $response['Total Belanja'] += $subtotal;
        }

        return response()->json($response, 200);
    }
}
