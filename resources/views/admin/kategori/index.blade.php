@extends('layouts.admin')
@section('title', 'Kategori Barang')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="font-serif text-2xl font-semibold">Kategori Barang</h1>
        <p class="text-sm text-gray-500">Kelompok klasifikasi data inventaris</p>
    </div>
    <a href="{{ route('kategori-barang.create') }}" class="bg-brand hover:bg-brand-dark text-white px-4 py-2.5 rounded-lg text-sm flex items-center gap-2 transition">
        <i class="ti ti-plus"></i> Tambah Kategori
    </a>
</div>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
            <tr><th class="p-3">Kode</th><th class="p-3">Nama Kategori</th><th class="p-3">Deskripsi</th><th class="p-3">Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($kategori as $item)
            <tr class="border-t border-gray-100">
                <td class="p-3"><span class="font-mono text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full">{{ $item->kode_kategori }}</span></td>
                <td class="p-3">{{ $item->nama_kategori }}</td>
                <td class="p-3 text-gray-500">{{ $item->deskripsi }}</td>
                <td class="p-3 space-x-3">
                    <a href="{{ route('kategori-barang.edit', $item->id) }}" class="text-brand hover:underline"><i class="ti ti-pencil"></i></a>
                    <form action="{{ route('kategori-barang.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline"><i class="ti ti-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-6 text-center text-gray-400">Belum ada data kategori.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection