<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';
    protected $fillable = ['id_user', 'jenis_laporan', 'format', 'periode_awal', 'periode_akhir', 'file_path'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}