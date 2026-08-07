<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pemeliharaan;
use Illuminate\Http\Request;

class PemeliharaanController extends Controller
{
    public function index()
    {
        $pemeliharaan = Pemeliharaan::with('barang')->latest()->get();
        return view('admin.pemeliharaan.index', compact('pemeliharaan'));
    }

    public function create()
    {
        $barangList = Barang::all();
        return view('admin.pemeliharaan.form', ['pemeliharaan' => null, 'barangList' => $barangList]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'tanggal_pemeliharaan' => 'required|date',
            'jenis_pemeliharaan' => 'required|string',
            'deskripsi' => 'nullable|string',
            'biaya' => 'nullable|numeric|min:0',
            'status' => 'required|in:dijadwalkan,proses,selesai',
        ]);
        $validated['id_user'] = auth()->id();
        Pemeliharaan::create($validated);
        return redirect()->route('admin.pemeliharaan.index')->with('success', 'Data pemeliharaan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pemeliharaan = Pemeliharaan::findOrFail($id);
        $barangList = Barang::all();
        return view('admin.pemeliharaan.form', compact('pemeliharaan', 'barangList'));
    }

    public function update(Request $request, $id)
    {
        $pemeliharaan = Pemeliharaan::findOrFail($id);
        $validated = $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'tanggal_pemeliharaan' => 'required|date',
            'jenis_pemeliharaan' => 'required|string',
            'deskripsi' => 'nullable|string',
            'biaya' => 'nullable|numeric|min:0',
            'status' => 'required|in:dijadwalkan,proses,selesai',
        ]);
        $pemeliharaan->update($validated);
        return redirect()->route('admin.pemeliharaan.index')->with('success', 'Data pemeliharaan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Pemeliharaan::findOrFail($id)->delete();
        return redirect()->route('admin.pemeliharaan.index')->with('success', 'Data pemeliharaan berhasil dihapus.');
    }
}