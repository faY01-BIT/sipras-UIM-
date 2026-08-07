<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('pemeliharaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_barang')->constrained('barang');
            $table->foreignId('id_user')->constrained('users');
            $table->date('tanggal_pemeliharaan');
            $table->string('jenis_pemeliharaan');
            $table->text('deskripsi')->nullable();
            $table->decimal('biaya',10,2)->nullable();
            $table->enum('status',['dijadwalkan','proses','selesai'])->default('dijadwalkan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeliharaan');
    }
};
