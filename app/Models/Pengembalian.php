<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';
    protected $fillable = [
        'id_peminjaman', 'id_admin_verifikasi', 'tanggal_kembali_aktual',
        'kondisi_barang', 'keterangan_kondisi', 'denda', 'status',
    ];

    public function peminjaman() { return $this->belongsTo(Peminjaman::class, 'id_peminjaman'); }
    public function adminVerifikasi() { return $this->belongsTo(User::class, 'id_admin_verifikasi'); }
}