<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriBarangController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjamanController;
use App\Http\Controllers\Mahasiswa\PeminjamanController as MahasiswaPeminjamanController;
use App\Http\Controllers\Admin\PengembalianController as AdminPengembalianController;
use App\Http\Controllers\Mahasiswa\PengembalianController as MahasiswaPengembalianController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Admin\PemeliharaanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/lupa-password', [LoginController::class, 'showForgotForm'])->name('password.request');
Route::post('/lupa-password', [LoginController::class, 'sendResetToken'])->name('password.email');
Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
    $stats = [
        'total_barang' => \App\Models\Barang::sum('jumlah_total'),
        'total_kategori' => \App\Models\KategoriBarang::count(),
        'peminjaman_pending' => \App\Models\Peminjaman::where('status', 'pending')->count(),
        'peminjaman_aktif' => \App\Models\Peminjaman::where('status', 'dipinjam')->count(),
    ];
    $terbaru = \App\Models\Peminjaman::with(['user', 'barang'])->latest()->limit(5)->get();
    return view('admin.dashboard', compact('stats', 'terbaru'));
})->name('admin.dashboard');

    Route::get('/kategori-barang', [KategoriBarangController::class, 'index'])->name('kategori-barang.index');
    Route::get('/kategori-barang/create', [KategoriBarangController::class, 'create'])->name('kategori-barang.create');
    Route::post('/kategori-barang', [KategoriBarangController::class, 'store'])->name('kategori-barang.store');
    Route::get('/kategori-barang/{id}/edit', [KategoriBarangController::class, 'edit'])->name('kategori-barang.edit');
    Route::put('/kategori-barang/{id}', [KategoriBarangController::class, 'update'])->name('kategori-barang.update');
    Route::delete('/kategori-barang/{id}', [KategoriBarangController::class, 'destroy'])->name('kategori-barang.destroy');

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

    Route::get('/peminjaman', [AdminPeminjamanController::class, 'index'])->name('admin.peminjaman.index');
    Route::post('/peminjaman/{id}/approve', [AdminPeminjamanController::class, 'approve'])->name('admin.peminjaman.approve');
    Route::post('/peminjaman/{id}/reject', [AdminPeminjamanController::class, 'reject'])->name('admin.peminjaman.reject');
    Route::post('/peminjaman/{id}/serah-terima', [AdminPeminjamanController::class, 'serahTerima'])->name('admin.peminjaman.serah-terima');

    Route::get('/pengembalian', [AdminPengembalianController::class, 'index'])->name('admin.pengembalian.index');
    Route::get('/pengembalian/{id}/proses', [AdminPengembalianController::class, 'proses'])->name('admin.pengembalian.proses');
    Route::post('/pengembalian/{id}/proses', [AdminPengembalianController::class, 'simpanProses'])->name('admin.pengembalian.simpan-proses');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
    Route::post('/laporan/generate-pdf', [LaporanController::class, 'generatePdf'])->name('admin.laporan.generate-pdf');
    Route::post('/laporan/generate-excel', [LaporanController::class, 'generateExcel'])->name('admin.laporan.generate-excel');

    Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('admin.pemeliharaan.index');
    Route::get('/pemeliharaan/create', [PemeliharaanController::class, 'create'])->name('admin.pemeliharaan.create');
    Route::post('/pemeliharaan', [PemeliharaanController::class, 'store'])->name('admin.pemeliharaan.store');
    Route::get('/pemeliharaan/{id}/edit', [PemeliharaanController::class, 'edit'])->name('admin.pemeliharaan.edit');
    Route::put('/pemeliharaan/{id}', [PemeliharaanController::class, 'update'])->name('admin.pemeliharaan.update');
    Route::delete('/pemeliharaan/{id}', [PemeliharaanController::class, 'destroy'])->name('admin.pemeliharaan.destroy');
});

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->group(function () {
    Route::get('/dashboard', function () {
    $uid = auth()->id();
    $stats = [
        'total_barang' => \App\Models\Barang::sum('jumlah_tersedia'),
        'peminjaman_aktif' => \App\Models\Peminjaman::where('id_user', $uid)->where('status', 'dipinjam')->count(),
        'menunggu' => \App\Models\Peminjaman::where('id_user', $uid)->where('status', 'pending')->count(),
        'selesai' => \App\Models\Peminjaman::where('id_user', $uid)->where('status', 'selesai')->count(),
    ];
    $terbaru = \App\Models\Peminjaman::with('barang')->where('id_user', $uid)->latest()->limit(5)->get();
    return view('mahasiswa.dashboard', compact('stats', 'terbaru'));
})->name('mahasiswa.dashboard');

    Route::get('/barang', [BarangController::class, 'indexMahasiswa'])->name('mahasiswa.barang.index');
    Route::get('/peminjaman', [MahasiswaPeminjamanController::class, 'index'])->name('mahasiswa.peminjaman.index');
    Route::get('/peminjaman/create', [MahasiswaPeminjamanController::class, 'create'])->name('mahasiswa.peminjaman.create');
    Route::post('/peminjaman', [MahasiswaPeminjamanController::class, 'store'])->name('mahasiswa.peminjaman.store');

    Route::get('/pengembalian', [MahasiswaPengembalianController::class, 'index'])->name('mahasiswa.pengembalian.index');
    Route::get('/pengembalian/create', [MahasiswaPengembalianController::class, 'create'])->name('mahasiswa.pengembalian.create');
    Route::post('/pengembalian', [MahasiswaPengembalianController::class, 'store'])->name('mahasiswa.pengembalian.store');
});