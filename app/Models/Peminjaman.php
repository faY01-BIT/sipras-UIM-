<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $fillable = [
        'id_user', 'id_barang', 'id_admin_verifikasi', 'tanggal_pinjam',
        'tanggal_kembali_rencana', 'jumlah_pinjam', 'status',
        'alasan_penolakan', 'tanggal_verifikasi', 'tanggal_serah_terima',
    ];

    public function user() { return $this->belongsTo(User::class, 'id_user'); }
    public function barang() { return $this->belongsTo(Barang::class, 'id_barang'); }
    public function pengembalian() { return $this->hasOne(Pengembalian::class, 'id_peminjaman'); }
    public function adminVerifikasi() { return $this->belongsTo(User::class, 'id_admin_verifikasi'); }
}