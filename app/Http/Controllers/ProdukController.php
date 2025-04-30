<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    // Menampilkan daftar produk
    public function index(Request $request)
{
    if (Auth::check()) {
        $user = Auth::user();

        if ($user->role == 'admin') {
            $kategoris = Kategori::all();
            $produkQuery = Produk::query();

            if ($request->filled('kategori_id')) {
                $produkQuery->where('kategori_id', $request->kategori_id);
            }

            if ($request->filled('search')) {
                $produkQuery->where('nama', 'like', '%' . $request->search . '%');
            }

            $produks = $produkQuery->latest()->paginate(10)->appends($request->only('kategori_id', 'search'));

            return view('adminhome', compact('produks', 'kategoris'));
        } elseif ($user->role == 'manager') {
            return redirect()->route('manager.home');
        } else {
            $kategoris = Kategori::all();
            $produkQuery = Produk::query();

            if ($request->filled('kategori_id')) {
                $produkQuery->where('kategori_id', $request->kategori_id);
            }

            if ($request->filled('search')) {
                $produkQuery->where('nama', 'like', '%' . $request->search . '%');
            }

            $produks = $produkQuery->paginate(10)->appends($request->only('kategori_id', 'search'));

            return view('home', compact('produks', 'kategoris'));
        }
    }

    return redirect()->route('login');
}

    // Menampilkan form tambah produk
    public function create()
    {
        $kategoris = Kategori::all();
        return view('produk.create', compact('kategoris'));
    }

    // Menyimpan produk baru
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

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $produk->image = $imageName;
        }

        $produk->update($request->only(['kode_produk', 'nama', 'harga', 'kategori_id']));

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    // Hapus produk
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
