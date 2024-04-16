<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokModel extends Model
{
    use HasFactory;
    protected $table = 't_stok'; //mendefinisikan nama tabel yg digunakan oleh model

    // public $timestamps = false;
    protected $primaryKey = 'stok_id'; //mendefinisikan primary key dr tabel yg digunakan
    protected $fillable = ['stok_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
    public function barang(): BelongsTo
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }
}