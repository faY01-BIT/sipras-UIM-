<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalian = Pengembalian::with('peminjaman.barang')
            ->whereHas('peminjaman', fn($q) => $q->where('id_user', auth()->id()))
            ->latest()->get();
        return view('mahasiswa.pengembalian.index', compact('pengembalian'));
    }

    public function create()
    {
        $peminjamanList = Peminjaman::where('id_user', auth()->id())
            ->where('status', 'dipinjam')
            ->whereDoesntHave('pengembalian')
            ->with('barang')->get();
        return view('mahasiswa.pengembalian.create', compact('peminjamanList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['id_peminjaman' => 'required|exists:peminjaman,id']);
        $peminjaman = Peminjaman::where('id_user', auth()->id())->where('status', 'dipinjam')->findOrFail($validated['id_peminjaman']);

        Pengembalian::create(['id_peminjaman' => $peminjaman->id, 'status' => 'pending']);
        return redirect()->route('mahasiswa.pengembalian.index')->with('success', 'Pengajuan pengembalian dikirim, menunggu konfirmasi admin.');
    }
}