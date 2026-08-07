<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users');
            $table->foreignId('id_barang')->constrained('barang');
            $table->foreignId('id_admin_verifikasi')->nullable()->constrained('users');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->integer('jumlah_pinjam');
            $table->enum('status',['pending','disetujui','ditolak','dipinjam','selesai'])->default('pending');
            $table->text('alasan_penolakan')->nullable();
            $table->dateTime('tanggal_verifikasi')->nullable();
            $table->dateTime('tanggal_serah_terima')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
