@extends('layouts.admin')
@section('title', 'Peminjaman')
@section('content')
<h1 class="font-serif text-2xl font-semibold mb-6">Kelola Peminjaman</h1>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
            <tr><th class="p-3">Peminjam</th><th class="p-3">Barang</th><th class="p-3">Jml</th><th class="p-3">Tgl Pinjam</th><th class="p-3">Status</th><th class="p-3">Aksi</th></tr>
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
                <td class="p-3">{{ $item->user->nama_lengkap ?? '-' }}</td>
                <td class="p-3">{{ $item->barang->nama_barang ?? '-' }}</td>
                <td class="p-3 font-mono">{{ $item->jumlah_pinjam }}</td>
                <td class="p-3 text-gray-500">{{ $item->tanggal_pinjam }}</td>
                <td class="p-3"><span class="font-mono text-[10px] uppercase tracking-wide {{ $badge[0] }} {{ $badge[1] }} px-2.5 py-1 rounded-full">{{ $item->status }}</span></td>
                <td class="p-3 space-x-1">
                    @if($item->status == 'pending')
                        <form action="{{ route('admin.peminjaman.approve', $item->id) }}" method="POST" class="inline">
                            @csrf
                            <button class="text-xs bg-brand-light text-brand-dark px-2.5 py-1.5 rounded-md hover:bg-brand hover:text-white transition">Setujui</button>
                        </form>
                        <form action="{{ route('admin.peminjaman.reject', $item->id) }}" method="POST" class="inline"
                            onsubmit="let a=prompt('Alasan penolakan:'); if(!a){return false;} this.querySelector('[name=alasan_penolakan]').value=a;">
                            @csrf<input type="hidden" name="alasan_penolakan" value="">
                            <button class="text-xs bg-red-50 text-red-600 px-2.5 py-1.5 rounded-md hover:bg-red-100 transition">Tolak</button>
                        </form>
                    @elseif($item->status == 'disetujui')
                        <form action="{{ route('admin.peminjaman.serah-terima', $item->id) }}" method="POST" class="inline">
                            @csrf<button class="text-xs bg-gold-bg text-gold-text px-2.5 py-1.5 rounded-md hover:bg-gold hover:text-ink transition">Konfirmasi Serah Terima</button>
                        </form>
                    @else <span class="text-gray-300">—</span> @endif
                </td>
            </tr>
            @empty <tr><td colspan="6" class="p-6 text-center text-gray-400">Belum ada pengajuan.</td></tr> @endforelse
        </tbody>
    </table>
</div>
@endsection