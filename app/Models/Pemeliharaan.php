<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeliharaan extends Model
{
    protected $table = 'pemeliharaan';
    protected $fillable = ['id_barang', 'id_user', 'tanggal_pemeliharaan', 'jenis_pemeliharaan', 'deskripsi', 'biaya', 'status'];

    public function barang() { return $this->belongsTo(Barang::class, 'id_barang'); }
    public function user() { return $this->belongsTo(User::class, 'id_user'); }
}