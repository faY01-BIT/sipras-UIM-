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
    <h2>Laporan Peminjaman - SIPRAS</h2>
    <p class="periode">Periode: {{ \Carbon\Carbon::parse($periodeAwal)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($periodeAkhir)->format('d M Y') }}</p>
    <table>
        <thead>
            <tr><th>No</th><th>Peminjam</th><th>Barang</th><th>Jumlah</th><th>Tgl Pinjam</th><th>Tgl Kembali Rencana</th><th>Status</th></tr>
        </thead>
        <tbody>
            @foreach($data as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->user->nama_lengkap }}</td>
                <td>{{ $item->barang->nama_barang }}</td>
                <td>{{ $item->jumlah_pinjam }}</td>
                <td>{{ $item->tanggal_pinjam }}</td>
                <td>{{ $item->tanggal_kembali_rencana }}</td>
                <td>{{ ucfirst($item->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p style="margin-top:30px;">Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
</body>
</html>