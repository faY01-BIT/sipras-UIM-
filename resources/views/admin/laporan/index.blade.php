@extends('layouts.admin')
@section('title', 'Laporan')
@section('content')
<h1 class="font-serif text-2xl font-semibold mb-6">Laporan</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <form method="POST" action="{{ route('admin.laporan.generate-pdf') }}" class="bg-white p-6 rounded-xl border border-gray-200">
        @csrf
        <h2 class="font-serif font-semibold mb-3">Generate Laporan PDF</h2>
        @if($errors->any())<div class="mb-3 p-2 bg-red-50 text-red-600 text-sm rounded-lg">{{ $errors->first() }}</div>@endif
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1 text-gray-600">Jenis Laporan</label>
            <select name="jenis_laporan" class="jenis-laporan-select w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand" required>
                <option value="peminjaman">Peminjaman</option>
                <option value="pemeliharaan">Pemeliharaan</option>
                <option value="inventaris">Inventaris</option>
            </select>
        </div>
        <div class="periode-fields grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600">Periode Awal</label>
                <input type="date" name="periode_awal" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600">Periode Akhir</label>
                <input type="date" name="periode_akhir" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand">
            </div>
        </div>
        <p class="inventaris-note text-xs text-gray-400 mb-4 hidden">Laporan Inventaris menampilkan kondisi barang saat ini, tidak memerlukan rentang tanggal.</p>
        <button type="submit" class="bg-brand hover:bg-brand-dark text-white px-4 py-2.5 rounded-lg text-sm flex items-center gap-2 transition">
            <x-icon name="file-type-pdf" />
            Generate PDF
        </button>
    </form>

    <form method="POST" action="{{ route('admin.laporan.generate-excel') }}" class="bg-white p-6 rounded-xl border border-gray-200">
        @csrf
        <h2 class="font-serif font-semibold mb-3">Generate Laporan Excel</h2>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1 text-gray-600">Jenis Laporan</label>
            <select name="jenis_laporan" class="jenis-laporan-select w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand" required>
                <option value="peminjaman">Peminjaman</option>
                <option value="pemeliharaan">Pemeliharaan</option>
                <option value="inventaris">Inventaris</option>
            </select>
        </div>
        <div class="periode-fields grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600">Periode Awal</label>
                <input type="date" name="periode_awal" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-600">Periode Akhir</label>
                <input type="date" name="periode_akhir" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand">
            </div>
        </div>
        <p class="inventaris-note text-xs text-gray-400 mb-4 hidden">Laporan Inventaris menampilkan kondisi barang saat ini, tidak memerlukan rentang tanggal.</p>
        <button type="submit" class="bg-gold hover:brightness-95 text-ink px-4 py-2.5 rounded-lg text-sm flex items-center gap-2 transition font-medium">
            <x-icon name="file-spreadsheet" />
            Generate Excel
        </button>
    </form>
</div>

<h2 class="font-serif font-semibold mb-3">Riwayat Laporan</h2>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
            <tr><th class="p-3">Jenis</th><th class="p-3">Format</th><th class="p-3">Periode</th><th class="p-3">Dibuat oleh</th><th class="p-3">Tanggal</th><th class="p-3">File</th></tr>
        </thead>
        <tbody>
            @forelse($riwayat as $item)
            <tr class="border-t border-gray-100">
                <td class="p-3">{{ ucfirst($item->jenis_laporan) }}</td>
                <td class="p-3"><span class="font-mono text-[10px] uppercase bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $item->format }}</span></td>
                <td class="p-3 text-gray-500">{{ $item->periode_awal }} s/d {{ $item->periode_akhir }}</td>
                <td class="p-3">{{ $item->user->nama_lengkap ?? '-' }}</td>
                <td class="p-3 text-gray-500">{{ $item->created_at->format('d M Y H:i') }}</td>
                <td class="p-3"><a href="{{ Storage::url($item->file_path) }}" target="_blank" class="text-brand hover:underline flex items-center gap-1">
                    <x-icon name="download" size="13" />
                    Unduh
                </a></td>
            </tr>
            @empty <tr><td colspan="6" class="p-6 text-center text-gray-400">Belum ada laporan dibuat.</td></tr> @endforelse
        </tbody>
    </table>
</div>

<script>
document.querySelectorAll('.jenis-laporan-select').forEach(function (select) {
    function toggle() {
        var form = select.closest('form');
        var periodeFields = form.querySelector('.periode-fields');
        var note = form.querySelector('.inventaris-note');
        var isInventaris = select.value === 'inventaris';
        var inputs = periodeFields.querySelectorAll('input');
        if (isInventaris) {
            periodeFields.classList.add('hidden');
            note.classList.remove('hidden');
            inputs.forEach(function (i) { i.removeAttribute('required'); });
        } else {
            periodeFields.classList.remove('hidden');
            note.classList.add('hidden');
            inputs.forEach(function (i) { i.setAttribute('required', 'required'); });
        }
    }
    select.addEventListener('change', toggle);
    toggle();
});
</script>
@endsection