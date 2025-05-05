<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    // Menampilkan semua kategori dengan pagination
    public function index()
    {
        $kategoris = Kategori::paginate(5); // JANGAN pakai ->get()
    
        return view('kategori.index', compact('kategoris'));
    }
    


    // Menampilkan form tambah kategori
    public function create()
    {
        return view('kategori.create');
    }

    // Menyimpan kategori baru ke database
    public function store(Request $request)
    {
        if (app()->environment() !== 'production') {
            DB::listen(function ($query) {
                logger($query->sql);
            });
        }
        

        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Kategori::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Menampilkan form edit kategori
    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    // Menyimpan perubahan kategori
    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kategori->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // Menghapus kategori
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
