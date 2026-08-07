<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $fillable = [
        'id_kategori', 'kode_barang', 'nama_barang', 'spesifikasi',
        'tahun_pengadaan', 'kondisi', 'keterangan_kondisi', 'merk',
        'jumlah_total', 'jumlah_tersedia',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori');
    }
}