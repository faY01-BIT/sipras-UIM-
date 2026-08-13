<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 4px; }
        p.periode { text-align: center; margin-top: 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Laporan Pemeliharaan - SIPRAS</h2>
    <p class="periode">Periode: {{ \Carbon\Carbon::parse($periodeAwal)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($periodeAkhir)->format('d M Y') }}</p>
    <table>
        <thead>
            <tr><th>No</th><th>Barang</th><th>Jenis Pemeliharaan</th><th>Deskripsi</th><th>Biaya</th><th>Tanggal</th><th>Status</th><th>Dicatat oleh</th></tr>
        </thead>
        <tbody>
            @forelse($data as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->barang->nama_barang }}</td>
                <td>{{ $item->jenis_pemeliharaan }}</td>
                <td>{{ $item->deskripsi ?? '-' }}</td>
                <td>{{ $item->biaya ? 'Rp ' . number_format($item->biaya, 0, ',', '.') : '-' }}</td>
                <td>{{ $item->tanggal_pemeliharaan }}</td>
                <td>{{ ucfirst($item->status) }}</td>
                <td>{{ $item->user->nama_lengkap }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;">Tidak ada data pemeliharaan pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
    <p style="margin-top:30px;">Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
</body>
</html>
