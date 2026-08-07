<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_peminjaman')->unique()->constrained('peminjaman');
            $table->foreignId('id_admin_verifikasi')->nullable()->constrained('users');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->enum('kondisi_barang', ['baik', 'rusak ringan', 'rusak berat'])->nullable();
            $table->text('keterangan_kondisi')->nullable();
            $table->decimal('denda', 10, 2)->default(0);
            $table->enum('status', ['pending', 'selesai'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
