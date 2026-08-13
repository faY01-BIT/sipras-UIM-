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
    <h2>Laporan Inventaris - SIPRAS</h2>
    <p class="periode">Kondisi per tanggal: {{ \Carbon\Carbon::parse($periodeAwal)->format('d M Y') }}</p>
    <table>
        <thead>
            <tr><th>No</th><th>Kode Barang</th><th>Nama Barang</th><th>Kategori</th><th>Kondisi</th><th>Jumlah Total</th><th>Jumlah Tersedia</th></tr>
        </thead>
        <tbody>
            @forelse($data as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->kode_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ ucfirst($item->kondisi ?? '-') }}</td>
                <td>{{ $item->jumlah_total }}</td>
                <td>{{ $item->jumlah_tersedia }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;">Belum ada data barang.</td></tr>
            @endforelse
        </tbody>
    </table>
    <p style="margin-top:30px;">Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
</body>
</html>
