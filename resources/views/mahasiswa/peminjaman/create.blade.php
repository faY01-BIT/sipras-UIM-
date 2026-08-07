@extends('layouts.mahasiswa')
@section('title', 'Ajukan Peminjaman')
@section('content')
<h1 class="font-serif text-2xl font-semibold mb-6">Ajukan Peminjaman</h1>
@if($errors->any())<div class="mb-4 p-3 bg-red-50 text-red-600 text-sm rounded-lg">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('mahasiswa.peminjaman.store') }}" class="bg-white p-8 rounded-xl border border-gray-200 max-w-lg space-y-5">
    @csrf
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih Barang</label>
        <select name="id_barang" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand" required>
            <option value="">-- Pilih Barang --</option>
            @foreach($barangList as $b)
            <option value="{{ $b->id }}" @selected(old('id_barang', $selectedBarangId) == $b->id)>{{ $b->nama_barang }} (tersedia: {{ $b->jumlah_tersedia }})</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jumlah Pinjam</label>
        <input type="number" name="jumlah_pinjam" value="{{ old('jumlah_pinjam', 1) }}" min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand" required>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Pinjam</label>
        <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+1 year')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand" required>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Rencana Tanggal Kembali</label>
        <input type="date" name="tanggal_kembali_rencana" value="{{ old('tanggal_kembali_rencana') }}" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+1 year')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand" required>
    </div>
    <div class="flex gap-3 pt-2">
        <button type="submit" class="bg-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
            <i class="ti ti-send"></i> Ajukan
        </button>
        <a href="{{ route('mahasiswa.barang.index') }}" class="px-5 py-2.5 rounded-lg text-sm border hover:bg-gray-50 transition">Batal</a>
    </div>
</form>
@endsection