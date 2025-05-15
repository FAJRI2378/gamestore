<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;


class ProdukController extends Controller
{
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

            // Admin bisa lihat semua produk
            if ($user->role == 'admin') {
                $produks = $produkQuery->latest()->paginate(10)->appends($request->only('search', 'kategori_id'));
                return view('adminhome', compact('produks', 'kategoris'));
            }

            // User biasa hanya lihat produk miliknya
            $produkQuery->where('user_id', $user->id);
            $produks = $produkQuery->latest()->paginate(10)->appends($request->only('search', 'kategori_id'));
            return view('home', compact('produks', 'kategoris'));
        }

        return redirect()->route('login');
    }

   public function create()
{
    $kategoris = Kategori::all();
    $users = User::all();
    return view('produk.create', compact('kategoris', 'users')); // <-- tambahkan 'users'
}


   public function store(Request $request)
{
    $query = Produk::with(['kategori', 'user'])
        ->where(function ($q) {
            $q->where('user_id', '!=', Auth::id())
              ->orWhereNull('user_id'); // Jika produk dibuat oleh admin tanpa user_id
        });

    if ($request->filled('search')) {
        $query->where('nama', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('kategori_id')) {
        $query->where('kategori_id', $request->kategori_id);
    }

    $produks = $query->latest()->paginate(9);
    $kategoris = Kategori::all();

    return view('game.store', compact('produks', 'kategoris'));
}

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

    public function edit(Produk $produk)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $produk->user_id !== $user->id) {
            abort(403, 'Anda tidak punya akses mengedit produk ini');
        }

        $kategoris = Kategori::all();
        return view('produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $produk->user_id !== $user->id) {
            abort(403, 'Anda tidak punya akses memperbarui produk ini');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stok' => 'required|integer|min:0',
        ]);

        $data = $request->only('nama', 'harga', 'kategori_id', 'stok');

        if ($request->hasFile('image')) {
            if ($produk->image && Storage::exists('public/images_produk/' . $produk->image)) {
                Storage::delete('public/images_produk/' . $produk->image);
            }
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Produk $produk)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $produk->user_id !== $user->id) {
            abort(403, 'Anda tidak punya akses menghapus produk ini');
        }

        if ($produk->image && Storage::exists('public/images_produk/' . $produk->image)) {
            Storage::delete('public/images_produk/' . $produk->image);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }

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

    public function show($id)
    {
        return redirect()->route('produk.index');
    }

    public function gameStore(Request $request)
    {
        $query = Produk::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $query->where('kategori_id', $request->kategori_id);
        }

        $produks = $query->with('kategori')->paginate(9);

        $kategoris = Kategori::all();

        return view('game-store', compact('produks', 'kategoris'));
    }
}
