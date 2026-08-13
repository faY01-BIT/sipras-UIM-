<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Peminjaman;
use App\Models\Pemeliharaan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index()
    {
        $riwayat = Laporan::with('user')->latest()->get();
        return view('admin.laporan.index', compact('riwayat'));
    }

    /**
     * Validasi input sesuai jenis laporan.
     * Laporan Inventaris bersifat snapshot (kondisi saat ini), sehingga tidak memerlukan rentang periode.
     */
    private function validateRequest(Request $request): array
    {
        $rules = ['jenis_laporan' => 'required|in:peminjaman,pemeliharaan,inventaris'];
        if ($request->input('jenis_laporan') !== 'inventaris') {
            $rules['periode_awal'] = 'required|date';
            $rules['periode_akhir'] = 'required|date|after_or_equal:periode_awal';
        }
        return $request->validate($rules);
    }

    /**
     * Ambil data laporan sesuai jenis yang dipilih.
     */
    private function ambilData(string $jenis, ?string $periodeAwal, ?string $periodeAkhir)
    {
        return match ($jenis) {
            'pemeliharaan' => Pemeliharaan::with(['barang', 'user'])
                ->whereBetween('tanggal_pemeliharaan', [$periodeAwal, $periodeAkhir])
                ->get(),
            'inventaris' => Barang::with('kategori')->orderBy('nama_barang')->get(),
            default => Peminjaman::with(['user', 'barang'])
                ->whereBetween('tanggal_pinjam', [$periodeAwal, $periodeAkhir])
                ->get(),
        };
    }

    public function generatePdf(Request $request)
    {
        $validated = $this->validateRequest($request);
        $jenis = $validated['jenis_laporan'];
        $periodeAwal = $validated['periode_awal'] ?? now()->toDateString();
        $periodeAkhir = $validated['periode_akhir'] ?? now()->toDateString();

        $data = $this->ambilData($jenis, $periodeAwal, $periodeAkhir);

        $pdf = Pdf::loadView("laporan.pdf-{$jenis}", [
            'data' => $data,
            'periodeAwal' => $periodeAwal,
            'periodeAkhir' => $periodeAkhir,
        ]);

        $filename = "laporan-{$jenis}-" . now()->format('Ymd-His') . '.pdf';
        $path = 'laporan/' . $filename;
        Storage::disk('public')->put($path, $pdf->output());

        Laporan::create([
            'id_user' => auth()->id(),
            'jenis_laporan' => $jenis,
            'format' => 'pdf',
            'periode_awal' => $periodeAwal,
            'periode_akhir' => $periodeAkhir,
            'file_path' => $path,
        ]);

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan PDF berhasil dibuat.');
    }

    public function generateExcel(Request $request)
    {
        $validated = $this->validateRequest($request);
        $jenis = $validated['jenis_laporan'];
        $periodeAwal = $validated['periode_awal'] ?? now()->toDateString();
        $periodeAkhir = $validated['periode_akhir'] ?? now()->toDateString();

        $data = $this->ambilData($jenis, $periodeAwal, $periodeAkhir);

        $csv = match ($jenis) {
            'pemeliharaan' => $this->csvPemeliharaan($data),
            'inventaris' => $this->csvInventaris($data),
            default => $this->csvPeminjaman($data),
        };

        $filename = "laporan-{$jenis}-" . now()->format('Ymd-His') . '.csv';
        $path = 'laporan/' . $filename;
        Storage::disk('public')->put($path, $csv);

        Laporan::create([
            'id_user' => auth()->id(),
            'jenis_laporan' => $jenis,
            'format' => 'excel',
            'periode_awal' => $periodeAwal,
            'periode_akhir' => $periodeAkhir,
            'file_path' => $path,
        ]);

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan Excel (CSV) berhasil dibuat.');
    }

    private function csvPeminjaman($data): string
    {
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
        return $csv;
    }

    private function csvPemeliharaan($data): string
    {
        $csv = "No,Barang,Jenis Pemeliharaan,Deskripsi,Biaya,Tanggal,Status,Dicatat oleh\n";
        foreach ($data as $i => $item) {
            $csv .= implode(',', [
                $i + 1,
                '"' . $item->barang->nama_barang . '"',
                '"' . $item->jenis_pemeliharaan . '"',
                '"' . str_replace(["\r", "\n"], ' ', $item->deskripsi ?? '-') . '"',
                $item->biaya ?? 0,
                $item->tanggal_pemeliharaan,
                ucfirst($item->status),
                '"' . $item->user->nama_lengkap . '"',
            ]) . "\n";
        }
        return $csv;
    }

    private function csvInventaris($data): string
    {
        $csv = "No,Kode Barang,Nama Barang,Kategori,Kondisi,Jumlah Total,Jumlah Tersedia\n";
        foreach ($data as $i => $item) {
            $csv .= implode(',', [
                $i + 1,
                $item->kode_barang,
                '"' . $item->nama_barang . '"',
                '"' . ($item->kategori->nama_kategori ?? '-') . '"',
                ucfirst($item->kondisi ?? '-'),
                $item->jumlah_total,
                $item->jumlah_tersedia,
            ]) . "\n";
        }
        return $csv;
    }
}