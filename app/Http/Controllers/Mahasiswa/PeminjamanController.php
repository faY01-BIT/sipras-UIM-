<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with('barang')->where('id_user', auth()->id())->latest()->get();
        return view('mahasiswa.peminjaman.index', compact('peminjaman'));
    }

    public function create(Request $request)
    {
        $barangList = Barang::where('jumlah_tersedia', '>', 0)->get();
        $selectedBarangId = $request->query('barang_id');
        return view('mahasiswa.peminjaman.create', compact('barangList', 'selectedBarangId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today|before_or_equal:' . now()->addYear()->format('Y-m-d'),
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam|before_or_equal:' . now()->addYear()->format('Y-m-d'),
        ]);

        $barang = Barang::findOrFail($validated['id_barang']);
        if ($validated['jumlah_pinjam'] > $barang->jumlah_tersedia) {
            return back()->withErrors(['jumlah_pinjam' => 'Jumlah melebihi stok tersedia (' . $barang->jumlah_tersedia . ').'])->withInput();
        }

        $validated['id_user'] = auth()->id();
        $validated['status'] = 'pending';
        Peminjaman::create($validated);

        return redirect()->route('mahasiswa.peminjaman.index')->with('success', 'Pengajuan berhasil dikirim, menunggu konfirmasi admin.');
    }
}