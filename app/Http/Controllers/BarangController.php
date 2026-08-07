<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('kategori')->latest()->get();
        return view('admin.barang.index', compact('barang'));
    }

    public function indexMahasiswa()
    {
        $barang = Barang::with('kategori')->get();
        return view('mahasiswa.barang.index', compact('barang'));
    }

    public function create()
    {
        $kategoriList = KategoriBarang::all();
        return view('admin.barang.form', ['barang' => null, 'kategoriList' => $kategoriList]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategori_barang,id',
            'nama_barang' => 'required|string',
            'spesifikasi' => 'nullable|string',
            'tahun_pengadaan' => 'nullable|digits:4',
            'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
            'keterangan_kondisi' => 'nullable|string',
            'merk' => 'nullable|string',
            'jumlah_total' => 'required|integer|min:0',
        ]);

        $nomor = Barang::count() + 1;
        $validated['kode_barang'] = 'BRG-' . str_pad($nomor, 3, '0', STR_PAD_LEFT);
        $validated['jumlah_tersedia'] = $validated['jumlah_total'];

        Barang::create($validated);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategoriList = KategoriBarang::all();
        return view('admin.barang.form', compact('barang', 'kategoriList'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategori_barang,id',
            'nama_barang' => 'required|string',
            'spesifikasi' => 'nullable|string',
            'tahun_pengadaan' => 'nullable|digits:4',
            'kondisi' => 'required|in:baik,rusak ringan,rusak berat',
            'keterangan_kondisi' => 'nullable|string',
            'merk' => 'nullable|string',
            'jumlah_total' => 'required|integer|min:0',
        ]);
        $barang->update($validated);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Barang::findOrFail($id)->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}