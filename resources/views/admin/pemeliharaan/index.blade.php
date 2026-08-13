@extends('layouts.admin')
@section('title', 'Pemeliharaan')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="font-serif text-2xl font-semibold">Data Pemeliharaan</h1>
        <p class="text-sm text-gray-500">Riwayat perawatan & perbaikan barang</p>
    </div>
    <a href="{{ route('admin.pemeliharaan.create') }}" class="bg-brand hover:bg-brand-dark text-white px-4 py-2.5 rounded-lg text-sm flex items-center gap-2 transition">
        <x-icon name="plus" /> Tambah Pemeliharaan
    </a>
</div>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
            <tr><th class="p-3">Barang</th><th class="p-3">Jenis</th><th class="p-3">Tanggal</th><th class="p-3">Biaya</th><th class="p-3">Status</th><th class="p-3">Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($pemeliharaan as $item)
            @php
                $badge = match($item->status) {
                    'selesai' => ['bg-brand-light', 'text-brand-dark'],
                    'proses' => ['bg-gold-bg', 'text-gold-text'],
                    default => ['bg-gray-100', 'text-gray-500'],
                };
            @endphp
            <tr class="border-t border-gray-100">
                <td class="p-3">{{ $item->barang->nama_barang ?? '-' }}</td>
                <td class="p-3 text-gray-500">{{ $item->jenis_pemeliharaan }}</td>
                <td class="p-3 text-gray-500">{{ $item->tanggal_pemeliharaan }}</td>
                <td class="p-3 font-mono">{{ $item->biaya ? 'Rp '.number_format($item->biaya,0,',','.') : '-' }}</td>
                <td class="p-3"><span class="font-mono text-[10px] uppercase tracking-wide {{ $badge[0] }} {{ $badge[1] }} px-2.5 py-1 rounded-full">{{ $item->status }}</span></td>
                <td class="p-3 space-x-3">
                    <a href="{{ route('admin.pemeliharaan.edit', $item->id) }}" class="text-brand hover:underline"><x-icon name="pencil" /></a>
                    <form action="{{ route('admin.pemeliharaan.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline"><x-icon name="trash" /></button>
                    </form>
                </td>
            </tr>
            @empty <tr><td colspan="6" class="p-6 text-center text-gray-400">Belum ada data pemeliharaan.</td></tr> @endforelse
        </tbody>
    </table>
</div>
@endsection