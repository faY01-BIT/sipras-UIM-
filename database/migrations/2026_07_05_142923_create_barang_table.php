<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kategori')->constrained('kategori_barang');
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->text('spesifikasi')->nullable();
            $table->year('tahun_pengadaan')->nullable();
            $table->enum('kondisi', ['baik', 'rusak ringan', 'rusak berat'])->default('baik');
            $table->text('keterangan_kondisi')->nullable();
            $table->string('merk')->nullable();
            $table->integer('jumlah_total');
            $table->integer('jumlah_tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
