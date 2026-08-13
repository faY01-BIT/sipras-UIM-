@extends('layouts.admin')
@section('title', 'Barang')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="font-serif text-2xl font-semibold">Data Barang</h1>
        <p class="text-sm text-gray-500">Kelola inventaris barang sarana & prasarana</p>
    </div>
    <a href="{{ route('barang.create') }}" class="bg-brand hover:bg-brand-dark text-white px-4 py-2.5 rounded-lg text-sm flex items-center gap-2 transition">
        <x-icon name="plus" /> Tambah Barang
    </a>
</div>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
            <tr><th class="p-3">Kode</th><th class="p-3">Nama</th><th class="p-3">Kategori</th><th class="p-3">Kondisi</th><th class="p-3">Tersedia/Total</th><th class="p-3">Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($barang as $item)
            @php
                $kondisiBadge = match($item->kondisi) {
                    'baik' => ['bg-brand-light', 'text-brand-dark'],
                    'rusak ringan' => ['bg-gold-bg', 'text-gold-text'],
                    default => ['bg-red-50', 'text-red-600'],
                };
            @endphp
            <tr class="border-t border-gray-100">
                <td class="p-3"><span class="font-mono text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full">{{ $item->kode_barang }}</span></td>
                <td class="p-3">{{ $item->nama_barang }}</td>
                <td class="p-3 text-gray-500">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                <td class="p-3"><span class="font-mono text-[10px] uppercase tracking-wide {{ $kondisiBadge[0] }} {{ $kondisiBadge[1] }} px-2.5 py-1 rounded-full">{{ $item->kondisi }}</span></td>
                <td class="p-3 font-mono">{{ $item->jumlah_tersedia }} / {{ $item->jumlah_total }}</td>
                <td class="p-3 space-x-3">
                    <a href="{{ route('barang.edit', $item->id) }}" class="text-brand hover:underline"><x-icon name="pencil" /></a>
                    <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline"><x-icon name="trash" /></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-6 text-center text-gray-400">Belum ada data barang.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection