@extends('layouts.admin')
@section('title', 'Pengembalian')
@section('content')
<h1 class="font-serif text-2xl font-semibold mb-6">Kelola Pengembalian</h1>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
            <tr><th class="p-3">Mahasiswa</th><th class="p-3">Barang</th><th class="p-3">Status</th><th class="p-3">Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($pengembalian as $item)
            @php
                $badge = $item->status == 'pending' ? ['bg-gold-bg', 'text-gold-text'] : ['bg-brand-light', 'text-brand-dark'];
            @endphp
            <tr class="border-t border-gray-100">
                <td class="p-3">{{ $item->peminjaman->user->nama_lengkap ?? '-' }}</td>
                <td class="p-3">{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                <td class="p-3"><span class="font-mono text-[10px] uppercase tracking-wide {{ $badge[0] }} {{ $badge[1] }} px-2.5 py-1 rounded-full">{{ $item->status }}</span></td>
                <td class="p-3">
                    @if($item->status == 'pending')
                    <a href="{{ route('admin.pengembalian.proses', $item->id) }}" class="text-brand hover:underline text-xs bg-brand-light px-2.5 py-1.5 rounded-md">Proses</a>
                    @else <span class="text-gray-300">—</span> @endif
                </td>
            </tr>
            @empty <tr><td colspan="4" class="p-6 text-center text-gray-400">Belum ada pengajuan.</td></tr> @endforelse
        </tbody>
    </table>
</div>
@endsection