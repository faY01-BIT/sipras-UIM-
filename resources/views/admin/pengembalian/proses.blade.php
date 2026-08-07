@extends('layouts.admin')
@section('title', 'Proses Pengembalian')
@section('content')
<h1 class="font-serif text-2xl font-semibold mb-6">Proses Pengembalian</h1>
<div class="bg-white p-6 rounded-xl border border-gray-200 max-w-lg mb-4">
    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Mahasiswa</p>
    <p class="font-medium mb-3">{{ $pengembalian->peminjaman->user->nama_lengkap }}</p>
    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Barang</p>
    <p class="font-medium">{{ $pengembalian->peminjaman->barang->nama_barang }} <span class="font-mono text-sm text-gray-400">({{ $pengembalian->peminjaman->jumlah_pinjam }} unit)</span></p>
</div>
@if($errors->any())<div class="mb-4 p-3 bg-red-50 text-red-600 text-sm rounded-lg">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('admin.pengembalian.simpan-proses', $pengembalian->id) }}" class="bg-white p-8 rounded-xl border border-gray-200 max-w-lg space-y-5">
    @csrf
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Kembali Aktual</label>
        <input type="date" name="tanggal_kembali_aktual" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand" required>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kondisi Barang</label>
        <select name="kondisi_barang" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand" required>
            @foreach(['baik', 'rusak ringan', 'rusak berat'] as $k)<option value="{{ $k }}">{{ ucfirst($k) }}</option>@endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Keterangan Kondisi</label>
        <textarea name="keterangan_kondisi" placeholder="Opsional" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand"></textarea>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Denda (Rp)</label>
        <input type="number" name="denda" min="0" placeholder="Kosongkan jika tidak ada" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand">
    </div>
    <button type="submit" class="bg-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">Simpan & Selesaikan</button>
</form>
@endsection