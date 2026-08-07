<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function index()
    {
        $kategori = KategoriBarang::latest()->get();
        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.form', ['kategori' => null]);
    }

    public function store(Request $request)
    {
    $validated = $request->validate([
        'nama_kategori' => 'required|string',
        'deskripsi' => 'nullable|string',
    ]);

    $nomor = KategoriBarang::count() + 1;
    $validated['kode_kategori'] = 'KAT-' . str_pad($nomor, 3, '0', STR_PAD_LEFT);

    KategoriBarang::create($validated);
    return redirect()->route('kategori-barang.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kategori = KategoriBarang::findOrFail($id);
        return view('admin.kategori.form', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
    $kategori = KategoriBarang::findOrFail($id);
    $validated = $request->validate([
        'nama_kategori' => 'required|string',
        'deskripsi' => 'nullable|string',
    ]);
    $kategori->update($validated);
    return redirect()->route('kategori-barang.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        KategoriBarang::findOrFail($id)->delete();
        return redirect()->route('kategori-barang.index')->with('success', 'Kategori berhasil dihapus.');
    }
}