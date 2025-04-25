<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = Kategori::all();
        // Coba tampilkan semua produk tanpa filter
        $produks = Produk::all();
    
        $user = $request->user();
        if ($user && $user->role == 'admin') {
            return view('adminhome', compact('produks', 'kategoris'));
        }
    
        return view('home', compact('produks', 'kategoris'));
    }
              

    public function create()
    {
        $kategoris = Kategori::all();
        return view('produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required',
            'nama' => 'required',
            'harga' => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $produk = new Produk();
        $produk->kode_produk = $request->kode_produk;
        $produk->nama = $request->nama;
        $produk->harga = $request->harga;
        $produk->kategori_id = $request->kategori_id;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $produk->image = $imageName;
        }

        $produk->save();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategoris = \App\Models\Kategori::all();
        return view('produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_produk' => 'required',
            'nama' => 'required',
            'harga' => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update($request->only(['kode_produk', 'nama', 'harga', 'kategori_id']));

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui');
    }


    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }
}
