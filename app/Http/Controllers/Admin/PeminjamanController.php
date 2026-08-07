<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['user', 'barang'])->latest()->get();
        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    public function approve($id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }
        if ($peminjaman->jumlah_pinjam > $peminjaman->barang->jumlah_tersedia) {
            return back()->with('error', 'Stok barang tidak lagi mencukupi.');
        }

        $peminjaman->update([
            'status' => 'disetujui',
            'id_admin_verifikasi' => auth()->id(),
            'tanggal_verifikasi' => now(),
        ]);
        $peminjaman->barang->decrement('jumlah_tersedia', $peminjaman->jumlah_pinjam);

        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate(['alasan_penolakan' => 'required|string']);
        $peminjaman = Peminjaman::findOrFail($id);
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $peminjaman->update([
            'status' => 'ditolak',
            'id_admin_verifikasi' => auth()->id(),
            'tanggal_verifikasi' => now(),
            'alasan_penolakan' => $validated['alasan_penolakan'],
        ]);

        return back()->with('success', 'Peminjaman ditolak.');
    }

    public function serahTerima($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Peminjaman belum disetujui atau sudah diserahkan.');
        }
        $peminjaman->update(['status' => 'dipinjam', 'tanggal_serah_terima' => now()]);
        return back()->with('success', 'Barang telah dikonfirmasi diserahkan.');
    }
}