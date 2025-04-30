<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // Menampilkan form untuk menambah kategori
    public function create()
    {
        return view('kategori.create');  // Pastikan ada view kategori.create
    }

    // Menyimpan kategori ke database
    public function store(Request $request)
    {
        // Validasi form
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        // Menyimpan kategori ke database
        Kategori::create([
            'nama' => $request->nama,
        ]);

        // Mengarahkan pengguna ke halaman admin.home setelah berhasil menambahkan kategori
        return redirect()->route('admin.home')->with('success', 'Kategori berhasil ditambahkan!');
    }
}
