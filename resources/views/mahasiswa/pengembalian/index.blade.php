@extends('layouts.mahasiswa')
@section('title', 'Pengembalian Saya')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="font-serif text-2xl font-semibold">Pengembalian Saya</h1>
    <a href="{{ route('mahasiswa.pengembalian.create') }}" class="bg-brand hover:bg-brand-dark text-white px-4 py-2.5 rounded-lg text-sm flex items-center gap-2 transition">
        <x-icon name="plus" /> Ajukan Pengembalian
    </a>
</div>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
            <tr><th class="p-3">Barang</th><th class="p-3">Status</th><th class="p-3">Kondisi</th><th class="p-3">Denda</th></tr>
        </thead>
        <tbody>
            @forelse($pengembalian as $item)
            @php
                $badge = $item->status == 'pending' ? ['bg-gold-bg', 'text-gold-text'] : ['bg-brand-light', 'text-brand-dark'];
            @endphp
            <tr class="border-t border-gray-100">
                <td class="p-3">{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                <td class="p-3"><span class="font-mono text-[10px] uppercase tracking-wide {{ $badge[0] }} {{ $badge[1] }} px-2.5 py-1 rounded-full">{{ $item->status }}</span></td>
                <td class="p-3 text-gray-500">{{ $item->kondisi_barang ?? '-' }}</td>
                <td class="p-3 font-mono">{{ $item->denda ? 'Rp '.number_format($item->denda,0,',','.') : '-' }}</td>
            </tr>
            @empty <tr><td colspan="4" class="p-6 text-center text-gray-400">Belum ada pengajuan.</td></tr> @endforelse
        </tbody>
    </table>
</div>
@endsection