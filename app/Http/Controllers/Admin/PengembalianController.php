<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalian = Pengembalian::with('peminjaman.barang', 'peminjaman.user')->latest()->get();
        return view('admin.pengembalian.index', compact('pengembalian'));
    }

    public function proses($id)
    {
        $pengembalian = Pengembalian::with('peminjaman.barang', 'peminjaman.user')->findOrFail($id);
        return view('admin.pengembalian.proses', compact('pengembalian'));
    }

    public function simpanProses(Request $request, $id)
    {
        $pengembalian = Pengembalian::with('peminjaman.barang')->findOrFail($id);
        $validated = $request->validate([
            'tanggal_kembali_aktual' => 'required|date',
            'kondisi_barang' => 'required|in:baik,rusak ringan,rusak berat',
            'keterangan_kondisi' => 'nullable|string',
            'denda' => 'nullable|numeric|min:0',
        ]);
        $validated['id_admin_verifikasi'] = auth()->id();
        $validated['status'] = 'selesai';
        $validated['denda'] = $validated['denda'] ?? 0;

        $pengembalian->update($validated);
        $pengembalian->peminjaman->update(['status' => 'selesai']);
        $pengembalian->peminjaman->barang->increment('jumlah_tersedia', $pengembalian->peminjaman->jumlah_pinjam);

        return redirect()->route('admin.pengembalian.index')->with('success', 'Pengembalian berhasil diproses.');
    }
}