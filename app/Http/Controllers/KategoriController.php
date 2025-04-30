<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        // Menampilkan semua kategori
        $kategoris = Kategori::all();
        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        // Menampilkan form untuk menambahkan kategori baru
        return view('kategori.create');
    }



public function store(Request $request)
{
    DB::listen(function ($query) {
        logger($query->sql);
    });

    // Validasi data
    $request->validate([
        'nama' => 'required|string|max:255',
    ]);

    // Menyimpan kategori baru
    Kategori::create([
        'nama' => $request->nama,
    ]);

    return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
}


    public function edit($id)
    {
        // Menampilkan form untuk mengedit kategori
        $kategori = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        // Mengupdate kategori
        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Menghapus kategori
        Kategori::findOrFail($id)->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}
