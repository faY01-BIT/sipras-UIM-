<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

   public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->foreignId('id_role')->nullable()->after('id')->constrained('roles')->onDelete('set null');
        $table->string('username')->unique()->after('id_role');
        $table->string('nama_lengkap')->after('username');
        $table->string('no_telp')->nullable()->after('email');
        $table->dropColumn('name');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['id_role']);
        $table->dropColumn(['id_role', 'username', 'nama_lengkap', 'no_telp']);
        $table->string('name')->after('id');
    });
}
};
