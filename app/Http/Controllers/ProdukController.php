<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $kategoris = Kategori::all();

        $produkQuery = Produk::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $produkQuery->where(function ($query) use ($search) {
                $query->where('nama', 'like', "%$search%")
                      ->orWhereHas('kategori', function ($q) use ($search) {
                          $q->where('nama', 'like', "%$search%");
                      });
            });
        }

        if ($request->filled('kategori_id')) {
            $produkQuery->where('kategori_id', $request->kategori_id);
        }

        // Jika bukan admin, batasi hanya produk milik user tersebut
        if ($user->role !== 'admin') {
            $produkQuery->where('user_id', $user->id);
        }

        $produks = $produkQuery->latest()->paginate(10)->appends($request->only('search', 'kategori_id'));

        $view = $user->role === 'admin' ? 'adminhome' : 'home';

        return view($view, compact('produks', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $users = User::all();

        return view('produk.create', compact('kategoris', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|string|unique:produks,kode_produk',
            'nama'        => 'required|string|max:255',
            'harga'       => 'required|numeric',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'stok'        => 'required|integer|min:0',
            'game'        => 'nullable|file|mimes:zip|max:51200',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('kode_produk', 'nama', 'harga', 'kategori_id', 'stok');
        $data['user_id'] = Auth::id();

        if ($request->hasFile('game')) {
            $data['game'] = $this->uploadFile($request->file('game'), 'games_produk');
        }

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadFile($request->file('image'), 'images_produk');
        }

        Produk::create($data);

        return redirect()->route('produk.index')->with('success', 'Game berhasil ditambahkan!');
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
        $request->validate([
            'kode_produk' => 'required|string|unique:produks,kode_produk,' . $produk->id,
            'nama'        => 'required|string|max:255',
            'harga'       => 'required|numeric',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'stok'        => 'required|integer|min:0',
            'game'        => 'nullable|file|mimes:zip|max:51200',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('kode_produk', 'nama', 'harga', 'kategori_id', 'stok');

        if ($request->hasFile('game')) {
            if ($produk->game && Storage::exists('public/games_produk/' . $produk->game)) {
                Storage::delete('public/games_produk/' . $produk->game);
            }
            $data['game'] = $this->uploadFile($request->file('game'), 'games_produk');
        }

        if ($request->hasFile('image')) {
            if ($produk->image && Storage::exists('public/images_produk/' . $produk->image)) {
                Storage::delete('public/images_produk/' . $produk->image);
            }
            $data['image'] = $this->uploadFile($request->file('image'), 'images_produk');
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Game berhasil diperbarui!');
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

        if ($produk->game && Storage::exists('public/games_produk/' . $produk->game)) {
            Storage::delete('public/games_produk/' . $produk->game);
        }

        $extractedPath = public_path('games_extracted/' . $produk->id);
        if (File::exists($extractedPath)) {
            File::deleteDirectory($extractedPath);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function gameStore(Request $request)
{
    $query = Produk::query();

    if ($request->filled('search')) {
        $query->where('nama', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('kategori_id')) {
        $query->where('kategori_id', $request->kategori_id);
    }

    $produks = $query->with('kategori', 'user')->paginate(9);
    $kategoris = Kategori::all();

    return view('game-store', compact('produks', 'kategoris'));
}
  
public function playGame(Produk $produk)
{
    if (!$produk->game || !Storage::exists('public/games_produk/' . $produk->game)) {
        return redirect()->back()->with('error', 'File game tidak ditemukan.');
    }

    $zipPath = storage_path('app/public/games_produk/' . $produk->game); // Lokasi file ZIP
    $extractPath = public_path('games_extracted/' . $produk->id); // Folder tujuan ekstrak di public/

    // Bersihkan folder lama kalau ada
    if (File::exists($extractPath)) {
        File::deleteDirectory($extractPath);
    }

    File::makeDirectory($extractPath, 0755, true); // Buat folder baru di public/

    $zip = new \ZipArchive();
    if ($zip->open($zipPath) === TRUE) {
        $zip->extractTo($extractPath); // Ekstrak ke public/games_extracted/{id}
        $zip->close();

        // Langsung cek apakah ada index.html di root
        if (file_exists($extractPath . '/index.html')) {
            return redirect(asset('games_extracted/' . $produk->id . '/index.html'));
        }

        // Jika tidak ada, cek subfolder
        $subdirs = array_filter(glob($extractPath . '/*'), 'is_dir');
        foreach ($subdirs as $dir) {
            if (file_exists($dir . '/index.html')) {
                File::copyDirectory($dir, $extractPath);
                File::deleteDirectory($dir);

                return redirect(asset('games_extracted/' . $produk->id . '/index.html'));
            }
        }

        return redirect()->back()->with('error', 'index.html tidak ditemukan.');
    }

    return redirect()->back()->with('error', 'Gagal membuka file ZIP.');
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

    private function uploadFile($file, $folder)
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $folderPath = 'public/' . $folder;

        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath);
        }

        $file->storeAs($folderPath, $filename);

        return $filename;
    }

    public function prepareGame(Produk $produk)
{
    if (!$produk->game || !Storage::exists('public/games_produk/' . $produk->game)) {
        return response()->json(['error' => 'File game tidak ditemukan.'], 404);
    }

    $zipPath = storage_path('app/public/games_produk/' . $produk->game);
    $extractPath = public_path('games_extracted/' . $produk->id);

    if (!file_exists($extractPath)) {
        mkdir($extractPath, 0755, true);
        $zip = new \ZipArchive();
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            return response()->json(['error' => 'Tidak dapat mengekstrak file game.'], 500);
        }
    }

    $indexFile = 'games_extracted/' . $produk->id . '/index.html';
    if (!file_exists(public_path($indexFile))) {
        return response()->json(['error' => 'File index.html tidak ditemukan setelah ekstraksi.'], 404);
    }

    return response()->json(['url' => url($indexFile)]);
}

}
