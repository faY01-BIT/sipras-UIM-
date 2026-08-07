@extends('layouts.mahasiswa')
@section('title', 'Peminjaman Saya')
@section('content')
<h1 class="font-serif text-2xl font-semibold mb-6">Peminjaman Saya</h1>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
            <tr><th class="p-3">Barang</th><th class="p-3">Jumlah</th><th class="p-3">Tgl Pinjam</th><th class="p-3">Rencana Kembali</th><th class="p-3">Status</th><th class="p-3">Alasan Ditolak</th></tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $item)
            @php
                $badge = match($item->status) {
                    'pending' => ['bg-gold-bg', 'text-gold-text'],
                    'disetujui' => ['bg-brand-light', 'text-brand-dark'],
                    'dipinjam' => ['bg-brand', 'text-white'],
                    'ditolak' => ['bg-red-50', 'text-red-600'],
                    default => ['bg-gray-100', 'text-gray-500'],
                };
            @endphp
            <tr class="border-t border-gray-100">
                <td class="p-3">{{ $item->barang->nama_barang ?? '-' }}</td>
                <td class="p-3 font-mono">{{ $item->jumlah_pinjam }}</td>
                <td class="p-3 text-gray-500">{{ $item->tanggal_pinjam }}</td>
                <td class="p-3 text-gray-500">{{ $item->tanggal_kembali_rencana }}</td>
                <td class="p-3"><span class="font-mono text-[10px] uppercase tracking-wide {{ $badge[0] }} {{ $badge[1] }} px-2.5 py-1 rounded-full">{{ $item->status }}</span></td>
                <td class="p-3 text-gray-500">{{ $item->alasan_penolakan }}</td>
            </tr>
            @empty <tr><td colspan="6" class="p-6 text-center text-gray-400">Belum ada pengajuan.</td></tr> @endforelse
        </tbody>
    </table>
</div>
@endsection