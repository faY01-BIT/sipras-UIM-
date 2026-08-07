<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $riwayat = Laporan::with('user')->latest()->get();
        return view('admin.laporan.index', compact('riwayat'));
    }

    public function generatePdf(Request $request)
    {
        $validated = $request->validate([
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
        ]);

        $data = Peminjaman::with(['user', 'barang'])
            ->whereBetween('tanggal_pinjam', [$validated['periode_awal'], $validated['periode_akhir']])
            ->get();

        $pdf = Pdf::loadView('laporan.pdf-peminjaman', [
            'data' => $data,
            'periodeAwal' => $validated['periode_awal'],
            'periodeAkhir' => $validated['periode_akhir'],
        ]);

        $filename = 'laporan-peminjaman-' . now()->format('Ymd-His') . '.pdf';
        $path = 'laporan/' . $filename;
        \Storage::disk('public')->put($path, $pdf->output());

        Laporan::create([
            'id_user' => auth()->id(),
            'jenis_laporan' => 'peminjaman',
            'format' => 'pdf',
            'periode_awal' => $validated['periode_awal'],
            'periode_akhir' => $validated['periode_akhir'],
            'file_path' => $path,
        ]);

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan PDF berhasil dibuat.');
    }

    public function generateExcel(Request $request)
    {
        $validated = $request->validate([
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
        ]);

        $data = Peminjaman::with(['user', 'barang'])
            ->whereBetween('tanggal_pinjam', [$validated['periode_awal'], $validated['periode_akhir']])
            ->get();

        $filename = 'laporan-peminjaman-' . now()->format('Ymd-His') . '.csv';
        $path = 'laporan/' . $filename;

        $csv = "No,Peminjam,Barang,Jumlah,Tanggal Pinjam,Rencana Kembali,Status\n";
        foreach ($data as $i => $item) {
            $csv .= implode(',', [
                $i + 1,
                '"' . $item->user->nama_lengkap . '"',
                '"' . $item->barang->nama_barang . '"',
                $item->jumlah_pinjam,
                $item->tanggal_pinjam,
                $item->tanggal_kembali_rencana,
                ucfirst($item->status),
            ]) . "\n";
        }

        \Storage::disk('public')->put($path, $csv);

        Laporan::create([
            'id_user' => auth()->id(),
            'jenis_laporan' => 'peminjaman',
            'format' => 'excel',
            'periode_awal' => $validated['periode_awal'],
            'periode_akhir' => $validated['periode_akhir'],
            'file_path' => $path,
        ]);

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan Excel (CSV) berhasil dibuat.');
    }
}