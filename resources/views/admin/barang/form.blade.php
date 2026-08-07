@extends('layouts.admin')
@section('title', $barang ? 'Edit Barang' : 'Tambah Barang')
@section('content')
<h1 class="font-serif text-2xl font-semibold mb-6">{{ $barang ? 'Edit' : 'Tambah' }} Barang</h1>
@if($errors->any())
<div class="mb-4 p-3 bg-red-50 text-red-600 text-sm rounded-lg">{{ $errors->first() }}</div>
@endif
<form method="POST" action="{{ $barang ? route('barang.update', $barang->id) : route('barang.store') }}" class="bg-white p-8 rounded-xl border border-gray-200 max-w-2xl space-y-5">
    @csrf
    @if($barang) @method('PUT') @endif

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori</label>
        <select name="id_kategori" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategoriList as $kat)
            <option value="{{ $kat->id }}" @selected(old('id_kategori', $barang->id_kategori ?? '') == $kat->id)>{{ $kat->nama_kategori }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Barang</label>
        <input type="text" name="nama_barang" placeholder="Contoh: Proyektor Epson" value="{{ old('nama_barang', $barang->nama_barang ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand" required>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Spesifikasi</label>
        <textarea name="spesifikasi" rows="3" placeholder="Detail spesifikasi barang (opsional)" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand">{{ old('spesifikasi', $barang->spesifikasi ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Merk</label>
            <input type="text" name="merk" placeholder="Opsional" value="{{ old('merk', $barang->merk ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tahun Pengadaan</label>
            <input type="number" name="tahun_pengadaan" placeholder="2024" value="{{ old('tahun_pengadaan', $barang->tahun_pengadaan ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jumlah Total</label>
            <input type="number" name="jumlah_total" placeholder="0" value="{{ old('jumlah_total', $barang->jumlah_total ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand" required min="0">
        </div>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kondisi</label>
        <select name="kondisi" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand" required>
            @foreach(['baik', 'rusak ringan', 'rusak berat'] as $k)
            <option value="{{ $k }}" @selected(old('kondisi', $barang->kondisi ?? 'baik') == $k)>{{ ucfirst($k) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Keterangan Kondisi</label>
        <textarea name="keterangan_kondisi" rows="2" placeholder="Catatan tambahan (opsional)" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand">{{ old('keterangan_kondisi', $barang->keterangan_kondisi ?? '') }}</textarea>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="bg-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">Simpan</button>
        <a href="{{ route('barang.index') }}" class="px-5 py-2.5 rounded-lg text-sm border hover:bg-gray-50 transition">Batal</a>
    </div>
</form>
@endsection