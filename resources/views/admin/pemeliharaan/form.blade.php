@extends('layouts.admin')
@section('title', $pemeliharaan ? 'Edit Pemeliharaan' : 'Tambah Pemeliharaan')
@section('content')
<h1 class="font-serif text-2xl font-semibold mb-6">{{ $pemeliharaan ? 'Edit' : 'Tambah' }} Pemeliharaan</h1>
@if($errors->any())<div class="mb-4 p-3 bg-red-50 text-red-600 text-sm rounded-lg">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ $pemeliharaan ? route('admin.pemeliharaan.update', $pemeliharaan->id) : route('admin.pemeliharaan.store') }}" class="bg-white p-8 rounded-xl border border-gray-200 max-w-lg space-y-5">
    @csrf
    @if($pemeliharaan) @method('PUT') @endif
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Barang</label>
        <select name="id_barang" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand" required>
            <option value="">-- Pilih Barang --</option>
            @foreach($barangList as $b)
            <option value="{{ $b->id }}" @selected(old('id_barang', $pemeliharaan->id_barang ?? '') == $b->id)>{{ $b->nama_barang }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Pemeliharaan</label>
        <input type="text" name="jenis_pemeliharaan" placeholder="misal: Servis Rutin, Perbaikan Kerusakan" value="{{ old('jenis_pemeliharaan', $pemeliharaan->jenis_pemeliharaan ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand" required>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal</label>
        <input type="date" name="tanggal_pemeliharaan" value="{{ old('tanggal_pemeliharaan', $pemeliharaan->tanggal_pemeliharaan ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand" required>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
        <textarea name="deskripsi" placeholder="Opsional" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand">{{ old('deskripsi', $pemeliharaan->deskripsi ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Biaya (Rp, opsional)</label>
        <input type="number" name="biaya" min="0" value="{{ old('biaya', $pemeliharaan->biaya ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand" required>
            @foreach(['dijadwalkan', 'proses', 'selesai'] as $s)
            <option value="{{ $s }}" @selected(old('status', $pemeliharaan->status ?? 'dijadwalkan') == $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex gap-3 pt-2">
        <button type="submit" class="bg-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">Simpan</button>
        <a href="{{ route('admin.pemeliharaan.index') }}" class="px-5 py-2.5 rounded-lg text-sm border hover:bg-gray-50 transition">Batal</a>
    </div>
</form>
@endsection