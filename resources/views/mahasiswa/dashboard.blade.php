@extends('layouts.mahasiswa')
@section('title', 'Dashboard')
@section('content')

<div class="bg-ink text-white rounded-2xl p-6 mb-6 flex items-center justify-between">
    <div>
        <h1 class="font-serif text-xl font-semibold mb-1">Halo, {{ auth()->user()->nama_lengkap }} 👋</h1>
        <p class="text-white/60 text-sm">Kamu memiliki {{ $stats['peminjaman_aktif'] }} peminjaman aktif. Pastikan barang dikembalikan tepat waktu.</p>
    </div>
    <div class="w-11 h-11 bg-gold rounded-full flex items-center justify-center">
        <i class="ti ti-flame text-ink text-xl"></i>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-5 rounded-xl border border-gray-200">
        <p class="text-xs text-gray-400 mb-1 tracking-wide">TOTAL</p>
        <p class="font-mono text-3xl font-semibold mb-1">{{ $stats['total_barang'] }}</p>
        <p class="text-sm text-gray-500">Barang Tersedia</p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200">
        <p class="text-xs text-brand mb-1 tracking-wide">AKTIF</p>
        <p class="font-mono text-3xl font-semibold mb-1">{{ $stats['peminjaman_aktif'] }}</p>
        <p class="text-sm text-gray-500">Peminjaman Aktif Saya</p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200">
        <p class="text-xs text-gold-text mb-1 tracking-wide">PENDING</p>
        <p class="font-mono text-3xl font-semibold mb-1">{{ $stats['menunggu'] }}</p>
        <p class="text-sm text-gray-500">Menunggu Konfirmasi</p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200">
        <p class="text-xs text-gray-400 mb-1 tracking-wide">SELESAI</p>
        <p class="font-mono text-3xl font-semibold mb-1">{{ $stats['selesai'] }}</p>
        <p class="text-sm text-gray-500">Total Peminjaman Selesai</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <span class="font-serif font-semibold">Peminjaman Saya Terbaru</span>
        <a href="{{ route('mahasiswa.peminjaman.index') }}" class="text-xs text-brand hover:underline flex items-center gap-1">
            Lihat semua <i class="ti ti-arrow-right text-sm"></i>
        </a>
    </div>
    @forelse($terbaru as $p)
        @php
            $badge = match($p->status) {
                'pending' => ['bg-gold-bg', 'text-gold-text'],
                'disetujui', 'dipinjam' => ['bg-brand-light', 'text-brand-dark'],
                'ditolak' => ['bg-red-50', 'text-red-600'],
                default => ['bg-gray-100', 'text-gray-500'],
            };
        @endphp
        <div class="flex items-center justify-between px-5 py-3 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
            <div class="flex items-center gap-3">
                <span class="font-mono text-xs {{ $badge[1] }} {{ $badge[0] }} px-2.5 py-1 rounded-full">{{ $p->barang->kode_barang ?? '-' }}</span>
                <div>
                    <p class="text-sm">{{ $p->barang->nama_barang ?? '-' }}</p>
                    <p class="text-xs text-gray-400">{{ $p->jumlah_pinjam }} unit · dipinjam {{ $p->tanggal_pinjam }}</p>
                </div>
            </div>
            <span class="font-mono text-[10px] tracking-wide {{ $badge[1] }} {{ $badge[0] }} px-2.5 py-1 rounded-full uppercase">{{ $p->status }}</span>
        </div>
    @empty
        <p class="px-5 py-6 text-center text-sm text-gray-400">Kamu belum pernah mengajukan peminjaman.</p>
    @endforelse
</div>
@endsection