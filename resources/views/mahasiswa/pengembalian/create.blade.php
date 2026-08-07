@extends('layouts.mahasiswa')
@section('title', 'Ajukan Pengembalian')
@section('content')
<h1 class="font-serif text-2xl font-semibold mb-6">Ajukan Pengembalian</h1>
@if($errors->any())<div class="mb-4 p-3 bg-red-50 text-red-600 text-sm rounded-lg">{{ $errors->first() }}</div>@endif
@if($peminjamanList->isEmpty())
<div class="bg-white p-6 rounded-xl border border-gray-200 max-w-lg text-gray-500 text-sm">
    Tidak ada peminjaman aktif yang bisa diajukan pengembaliannya.
</div>
@else
<form method="POST" action="{{ route('mahasiswa.pengembalian.store') }}" class="bg-white p-8 rounded-xl border border-gray-200 max-w-lg">
    @csrf
    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih Peminjaman</label>
    <select name="id_peminjaman" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 mb-6 focus:outline-none focus:ring-2 focus:ring-brand" required>
        <option value="">-- Pilih --</option>
        @foreach($peminjamanList as $p)
        <option value="{{ $p->id }}">{{ $p->barang->nama_barang }} ({{ $p->jumlah_pinjam }} unit, dipinjam {{ $p->tanggal_pinjam }})</option>
        @endforeach
    </select>
    <button type="submit" class="bg-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">Ajukan Pengembalian</button>
</form>
@endif
@endsection