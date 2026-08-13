@extends('layouts.mahasiswa')
@section('title', 'Lihat Inventaris')
@section('content')
<h1 class="font-serif text-2xl font-semibold mb-1">Daftar Inventaris</h1>
<p class="text-sm text-gray-500 mb-6">Barang yang tersedia untuk dipinjam</p>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
            <tr><th class="p-3">Kode</th><th class="p-3">Nama</th><th class="p-3">Kategori</th><th class="p-3">Kondisi</th><th class="p-3">Tersedia</th><th class="p-3">Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($barang as $item)
            <tr class="border-t border-gray-100">
                <td class="p-3"><span class="font-mono text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full">{{ $item->kode_barang }}</span></td>
                <td class="p-3">{{ $item->nama_barang }}</td>
                <td class="p-3 text-gray-500">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                <td class="p-3 text-gray-500">{{ $item->kondisi }}</td>
                <td class="p-3 font-mono">{{ $item->jumlah_tersedia }}</td>
                <td class="p-3">
                    @if($item->jumlah_tersedia > 0)
                    <a href="{{ route('mahasiswa.peminjaman.create', ['barang_id' => $item->id]) }}" class="text-brand font-medium hover:underline flex items-center gap-1">
                        <x-icon name="clipboard-plus" /> Ajukan Pinjam
                    </a>
                    @else <span class="text-gray-400">Tidak tersedia</span> @endif
                </td>
            </tr>
            @empty <tr><td colspan="6" class="p-6 text-center text-gray-400">Belum ada data barang.</td></tr> @endforelse
        </tbody>
    </table>
</div>
@endsection