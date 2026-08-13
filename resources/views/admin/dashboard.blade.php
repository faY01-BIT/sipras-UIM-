@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')

<div class="bg-ink text-white rounded-2xl p-6 mb-6 flex items-center justify-between">
    <div>
        <h1 class="font-serif text-xl font-semibold mb-1">Halo, {{ auth()->user()->nama_lengkap }}</h1>
        <p class="text-white/60 text-sm">Selamat datang kembali di panel admin SIPRAS.</p>
    </div>
    <div class="w-11 h-11 bg-gold rounded-full flex items-center justify-center">
        <x-icon name="flame" size="20" class="text-ink" />
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-5 rounded-xl border border-gray-200">
        <p class="text-xs text-gray-400 mb-1 tracking-wide">TOTAL</p>
        <p class="font-mono text-3xl font-semibold mb-1">{{ $stats['total_barang'] }}</p>
        <p class="text-sm text-gray-500">Total Barang Terdaftar</p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200">
        <p class="text-xs text-gray-400 mb-1 tracking-wide">KATEGORI</p>
        <p class="font-mono text-3xl font-semibold mb-1">{{ $stats['total_kategori'] }}</p>
        <p class="text-sm text-gray-500">Kategori Barang</p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200">
        <p class="text-xs text-gold-text mb-1 tracking-wide">PENDING</p>
        <p class="font-mono text-3xl font-semibold mb-1">{{ $stats['peminjaman_pending'] }}</p>
        <p class="text-sm text-gray-500">Menunggu Konfirmasi</p>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-200">
        <p class="text-xs text-brand mb-1 tracking-wide">AKTIF</p>
        <p class="font-mono text-3xl font-semibold mb-1">{{ $stats['peminjaman_aktif'] }}</p>
        <p class="text-sm text-gray-500">Sedang Dipinjam</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <span class="font-serif font-semibold">Barang Tersedia</span>
        <a href="{{ route('barang.index') }}" class="text-xs text-brand hover:underline flex items-center gap-1">
            Lihat semua <x-icon name="arrow-right" size="14" />
        </a>
    </div>
    
    @forelse($barangTersedia as $b)
        <div class="flex items-center justify-between px-5 py-3 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
            <div class="flex items-center gap-3">
                <span class="font-mono text-xs text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">{{ $b->kode_barang }}</span>
                <div>
                    <p class="text-sm">{{ $b->nama_barang }}</p>
                    <p class="text-xs text-gray-400">{{ $b->kategori->nama_kategori ?? '-' }}</p>
                </div>
            </div>
            <span class="font-mono text-xs px-2.5 py-1 rounded-full {{ $b->jumlah_tersedia == 0 ? 'text-red-600 bg-red-50' : 'text-brand-dark bg-brand-light' }}">
                {{ $b->jumlah_tersedia }} / {{ $b->jumlah_total }} unit
            </span>
        </div>
    @empty
        <p class="px-5 py-6 text-center text-sm text-gray-400">Belum ada data barang.</p>
    @endforelse
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <span class="font-serif font-semibold">Pengajuan Peminjaman Terbaru</span>
        <a href="{{ route('admin.peminjaman.index') }}" class="text-xs text-brand hover:underline flex items-center gap-1">
            Lihat semua <x-icon name="arrow-right" size="14" />
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
                    <p class="text-xs text-gray-400">{{ $p->user->nama_lengkap ?? '-' }} · {{ $p->jumlah_pinjam }} unit</p>
                </div>
            </div>
            <span class="font-mono text-[10px] tracking-wide {{ $badge[1] }} {{ $badge[0] }} px-2.5 py-1 rounded-full uppercase">{{ $p->status }}</span>
        </div>
    @empty
        <p class="px-5 py-6 text-center text-sm text-gray-400">Belum ada pengajuan peminjaman.</p>
    @endforelse
</div>
@endsection