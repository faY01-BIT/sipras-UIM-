@extends('layouts.admin')
@section('title', $kategori ? 'Edit Kategori' : 'Tambah Kategori')
@section('content')
<h1 class="font-serif text-2xl font-semibold mb-6">{{ $kategori ? 'Edit' : 'Tambah' }} Kategori Barang</h1>
@if($errors->any())
<div class="mb-4 p-3 bg-red-50 text-red-600 text-sm rounded-lg">{{ $errors->first() }}</div>
@endif
<form method="POST" action="{{ $kategori ? route('kategori-barang.update', $kategori->id) : route('kategori-barang.store') }}" class="bg-white p-8 rounded-xl border border-gray-200 max-w-lg space-y-5">
    @csrf
    @if($kategori) @method('PUT') @endif

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Kategori</label>
        <input type="text" name="nama_kategori" placeholder="Contoh: Elektronik" value="{{ old('nama_kategori', $kategori->nama_kategori ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand" required>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
        <textarea name="deskripsi" rows="3" placeholder="Deskripsi kategori (opsional)" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand">{{ old('deskripsi', $kategori->deskripsi ?? '') }}</textarea>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="bg-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">Simpan</button>
        <a href="{{ route('kategori-barang.index') }}" class="px-5 py-2.5 rounded-lg text-sm border hover:bg-gray-50 transition">Batal</a>
    </div>
</form>
@endsection