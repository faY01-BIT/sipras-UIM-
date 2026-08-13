<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Peminjaman;
use App\Models\Pemeliharaan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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

        [$judul, $headers, $rows] = match ($jenis) {
            'pemeliharaan' => $this->siapkanPemeliharaan($data),
            'inventaris' => $this->siapkanInventaris($data),
            default => $this->siapkanPeminjaman($data),
        };

        $spreadsheet = $this->buatSheetRapi($judul, $headers, $rows, $periodeAwal, $periodeAkhir, $jenis === 'inventaris');

        $filename = "laporan-{$jenis}-" . now()->format('Ymd-His') . '.xlsx';
        $path = 'laporan/' . $filename;
        $fullPath = Storage::disk('public')->path($path);
        Storage::disk('public')->makeDirectory('laporan');
        (new Xlsx($spreadsheet))->save($fullPath);

        Laporan::create([
            'id_user' => auth()->id(),
            'jenis_laporan' => $jenis,
            'format' => 'excel',
            'periode_awal' => $periodeAwal,
            'periode_akhir' => $periodeAkhir,
            'file_path' => $path,
        ]);

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan Excel berhasil dibuat.');
    }

    /**
     * Bangun file .xlsx dengan styling rapi: header tebal berwarna, kolom auto-width, border tabel.
     */
    private function buatSheetRapi(string $judul, array $headers, array $rows, string $periodeAwal, string $periodeAkhir, bool $tanpaPeriode = false): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan');

        // Judul
        $lastCol = chr(ord('A') + count($headers) - 1);
        $sheet->setCellValue('A1', $judul);
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Periode / keterangan
        $ket = $tanpaPeriode
            ? 'Kondisi per tanggal: ' . \Carbon\Carbon::parse($periodeAwal)->format('d M Y')
            : 'Periode: ' . \Carbon\Carbon::parse($periodeAwal)->format('d M Y') . ' s/d ' . \Carbon\Carbon::parse($periodeAkhir)->format('d M Y');
        $sheet->setCellValue('A2', $ket);
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->getColor()->setRGB('555555');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header tabel (baris 4)
        $headerRow = 4;
        foreach ($headers as $i => $h) {
            $col = chr(ord('A') + $i);
            $sheet->setCellValue("{$col}{$headerRow}", $h);
        }
        $headerRange = "A{$headerRow}:{$lastCol}{$headerRow}";
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('0F766E');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($headerRow)->setRowHeight(22);

        // Data
        $r = $headerRow + 1;
        foreach ($rows as $row) {
            foreach ($row as $i => $val) {
                $col = chr(ord('A') + $i);
                $sheet->setCellValue("{$col}{$r}", $val);
            }
            $r++;
        }
        $lastRow = $r - 1;

        if ($lastRow >= $headerRow + 1) {
            $dataRange = "A" . ($headerRow + 1) . ":{$lastCol}{$lastRow}";
            $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('D1D5DB');
            // selang-seling warna baris biar gampang dibaca
            for ($row = $headerRow + 1; $row <= $lastRow; $row++) {
                if (($row - $headerRow) % 2 === 0) {
                    $sheet->getStyle("A{$row}:{$lastCol}{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F6F4EF');
                }
            }
        } else {
            $sheet->setCellValue("A" . ($headerRow + 1), 'Tidak ada data.');
            $sheet->mergeCells("A" . ($headerRow + 1) . ":{$lastCol}" . ($headerRow + 1));
            $sheet->getStyle("A" . ($headerRow + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Auto-width tiap kolom
        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getStyle("A{$headerRow}:{$lastCol}{$headerRow}")->getAlignment()->setWrapText(true);

        return $spreadsheet;
    }

    private function siapkanPeminjaman($data): array
    {
        $headers = ['No', 'Peminjam', 'Barang', 'Jumlah', 'Tanggal Pinjam', 'Rencana Kembali', 'Status'];
        $rows = [];
        foreach ($data as $i => $item) {
            $rows[] = [
                $i + 1,
                $item->user->nama_lengkap,
                $item->barang->nama_barang,
                $item->jumlah_pinjam,
                $item->tanggal_pinjam,
                $item->tanggal_kembali_rencana,
                ucfirst($item->status),
            ];
        }
        return ['Laporan Peminjaman - SIPRAS', $headers, $rows];
    }

    private function siapkanPemeliharaan($data): array
    {
        $headers = ['No', 'Barang', 'Jenis Pemeliharaan', 'Deskripsi', 'Biaya', 'Tanggal', 'Status', 'Dicatat oleh'];
        $rows = [];
        foreach ($data as $i => $item) {
            $rows[] = [
                $i + 1,
                $item->barang->nama_barang,
                $item->jenis_pemeliharaan,
                $item->deskripsi ?? '-',
                $item->biaya ?? 0,
                $item->tanggal_pemeliharaan,
                ucfirst($item->status),
                $item->user->nama_lengkap,
            ];
        }
        return ['Laporan Pemeliharaan - SIPRAS', $headers, $rows];
    }

    private function siapkanInventaris($data): array
    {
        $headers = ['No', 'Kode Barang', 'Nama Barang', 'Kategori', 'Kondisi', 'Jumlah Total', 'Jumlah Tersedia'];
        $rows = [];
        foreach ($data as $i => $item) {
            $rows[] = [
                $i + 1,
                $item->kode_barang,
                $item->nama_barang,
                $item->kategori->nama_kategori ?? '-',
                ucfirst($item->kondisi ?? '-'),
                $item->jumlah_total,
                $item->jumlah_tersedia,
            ];
        }
        return ['Laporan Inventaris - SIPRAS', $headers, $rows];
    }
}