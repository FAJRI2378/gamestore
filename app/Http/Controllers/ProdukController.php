<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    // Menampilkan daftar produk dengan filter pencarian
    public function index(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            $kategoris = Kategori::all();
            $produkQuery = Produk::query();

            if ($request->filled('search')) {
                $produkQuery->where(function ($query) use ($request) {
                    $query->where('nama', 'like', '%' . $request->search . '%')
                          ->orWhereHas('kategori', function ($q) use ($request) {
                              $q->where('nama', 'like', '%' . $request->search . '%');
                          });
                });
            }

            // Admin view
            if ($user->role == 'admin') {
                $produks = $produkQuery->latest()->paginate(10)->appends($request->only('search', 'kategori_id'));
                return view('adminhome', compact('produks', 'kategoris'));
            }

            // Manager view
            if ($user->role == 'manager') {
                return redirect()->route('manager.home');
            }

            // Default user view
            $produks = $produkQuery->paginate(10)->appends($request->only('search', 'kategori_id'));
            return view('home', compact('produks', 'kategoris'));
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
    // Validasi inputan
    $request->validate([
        'kode_produk' => 'required|unique:produks,kode_produk',
        'nama' => 'required',
        'harga' => 'required|numeric',
        'kategori_id' => 'required|exists:kategoris,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'stok' => 'required|integer|min:0',
    ]);

    // Ambil data dari request
    $data = $request->only('kode_produk', 'nama', 'harga', 'kategori_id', 'stok');

    // Jika ada gambar yang diupload, proses upload
    if ($request->hasFile('image')) {
        $data['image'] = $this->uploadImage($request->file('image'));
    }

    // Simpan produk baru ke database
    Produk::create($data);

    return redirect()->route('admin.home')->with('success', 'Produk berhasil ditambahkan!');
}


    // Upload gambar
    private function uploadImage($file)
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $folderPath = 'public/images_produk';

        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath);
        }

        $file->storeAs($folderPath, $filename);
        return $filename;
    }

    // Form edit produk
    public function edit(Produk $produk)
    {
        $kategoris = Kategori::all();
        return view('produk.edit', compact('produk', 'kategoris'));
    }

    // Update produk
    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stok' => 'required|integer|min:0',
        ]);

        $data = $request->only('nama', 'harga', 'kategori_id');

        if ($request->hasFile('image')) {
            if ($produk->image && Storage::exists('public/images_produk/' . $produk->image)) {
                Storage::delete('public/images_produk/' . $produk->image);
            }
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        $produk->update($data);

        return redirect()->route('admin.home')->with('success', 'Produk berhasil diperbarui!');
    }

    // Hapus produk
    public function destroy(Produk $produk)
    {
        if ($produk->image && Storage::exists('public/images_produk/' . $produk->image)) {
            Storage::delete('public/images_produk/' . $produk->image);
        }

        $produk->delete();

        return redirect()->route('admin.home')->with('success', 'Produk berhasil dihapus!');
    }

    // AJAX Live Search
    public function liveSearch(Request $request)
    {
        $search = $request->get('search');
        $produks = Produk::where('nama', 'like', "%$search%")
            ->orWhereHas('kategori', function ($query) use ($search) {
                $query->where('nama', 'like', "%$search%");
            })
            ->limit(10)
            ->get();

        return view('partials.search_results', compact('produks'));
    }

    // Tambahan untuk Route::resource agar tidak error
    public function show($id)
    {
        return redirect()->route('produk.index');
    }
}
