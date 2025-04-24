<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // Menampilkan daftar produk dengan filter kategori dan pencarian
    public function index(Request $request)
{
    $kategoris = Kategori::all(); // Ambil semua kategori untuk filter

    $produkQuery = Produk::query();

    // Pencarian berdasarkan kategori jika ada
    if ($request->filled('search')) {
        $produkQuery->whereHas('kategori', function ($query) use ($request) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        });
    }

    // Pagination (10 produk per halaman)
    $produks = $produkQuery->paginate(10);

    // Kirim data ke view
    return view('home', compact('produks', 'kategoris'));
}


    // Menampilkan form tambah produk
    public function create()
    {
        $kategoris = Kategori::all();
        return view('produk.create', compact('kategoris'));
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|unique:produks',
            'nama' => 'required',
            'harga' => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        Produk::create($request->all());

        // Redirect ke admin.home setelah sukses tambah produk
        return redirect()->route('admin.home')->with('success', 'Produk berhasil ditambahkan');
    }

    // Menampilkan form edit produk
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategoris = Kategori::all();

        return view('produk.edit', compact('produk', 'kategoris'));
    }

    // Update data produk
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_produk' => 'required',
            'nama' => 'required',
            'harga' => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update($request->all());

        // Redirect ke admin.home setelah sukses update produk
        return redirect()->route('admin.home')->with('success', 'Produk berhasil diperbarui');
    }

    // Hapus produk
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        // Redirect ke admin.home setelah sukses hapus produk
        return redirect()->route('admin.home')->with('success', 'Produk berhasil dihapus');
    }
}
