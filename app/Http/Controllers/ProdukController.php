<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\User;
use App\Models\Transaction;
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

        $produk = Produk::create($data);

        if ($produk->game) {
            $folderName = $this->extractGameZip($produk, $produk->game);
            $produk->update(['nama_folder' => $folderName]);
        }

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

        if (isset($data['game'])) {
            $folderName = $this->extractGameZip($produk, $data['game']);
            $produk->update(['nama_folder' => $folderName]);
        }

        return redirect()->route('produk.index')->with('success', 'Game berhasil diperbarui!');
    }

public function destroy($id)
{
    $produk = Produk::find($id);

    if (!$produk) {
        return redirect()->route('produk.index')->with('error', 'Produk tidak ditemukan.');
    }

    $user = Auth::user();

    if ($user->role !== 'admin' && $produk->user_id !== $user->id) {
        abort(403, 'Anda tidak punya akses menghapus produk ini');
    }

    // Hapus file gambar
    if ($produk->image && Storage::exists('public/images_produk/' . $produk->image)) {
        Storage::delete('public/images_produk/' . $produk->image);
    }

    // Hapus file game
    if ($produk->game && Storage::exists('public/games_produk/' . $produk->game)) {
        Storage::delete('public/games_produk/' . $produk->game);
    }

    // Hapus folder hasil ekstrak
    $extractedPath = public_path('games_extracted/' . $produk->nama_folder);
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

        $produks = $query->whereNotNull('game')->latest()->paginate(9)->appends($request->only('search', 'kategori_id'));
        $kategoris = Kategori::all();

        return view('game-store', compact('produks', 'kategoris'));
    }


public function playGame(Produk $produk)
{
    if (!$produk->game) {
        return response()->json([
            'success' => false,
            'message' => 'File game tidak tersedia.'
        ]);
    }

    $zipPath = storage_path('app/public/games_produk/' . $produk->game);

    if (!file_exists($zipPath)) {
        return response()->json([
            'success' => false,
            'message' => 'File ZIP tidak ditemukan.'
        ]);
    }

    $zip = new \ZipArchive();
    $folderName = null;

    if ($zip->open($zipPath) === TRUE) {
        $rootFolders = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $fileName = $stat['name'];
            $parts = explode('/', $fileName);
            if (count($parts) > 1) {
                $rootFolders[] = $parts[0];
            }
        }

        $zip->close();
        $rootFolders = array_unique($rootFolders);

        if (count($rootFolders) === 1) {
            $folderName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $rootFolders[0]);
        } else {
            $folderName = pathinfo($produk->game, PATHINFO_FILENAME);
            $folderName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $folderName);
        }
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Gagal membuka file ZIP.'
        ]);
    }

    $extractPath = public_path('games_extracted/' . $folderName);

    // Ekstrak otomatis jika folder belum ada atau index.html belum ditemukan
    if (!File::exists($extractPath)) {
        File::makeDirectory($extractPath, 0755, true);
    }

    // Buka dan ekstrak ZIP ke folder tujuan
    $zip = new \ZipArchive();
    if ($zip->open($zipPath) === TRUE) {
        // Hapus dulu folder lama kalau ada
        if (File::exists($extractPath)) {
            File::deleteDirectory($extractPath);
            File::makeDirectory($extractPath, 0755, true);
        }

        $zip->extractTo($extractPath);
        $zip->close();
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Gagal membuka file ZIP untuk ekstraksi.'
        ]);
    }

    // Cari file index.html secara rekursif
    $indexPath = $this->findIndexHtml($extractPath);

    if (!$indexPath) {
        return response()->json([
            'success' => false,
            'message' => 'File index.html tidak ditemukan setelah ekstrak.'
        ]);
    }

    // Buat path relatif untuk URL
    $relativePath = str_replace(public_path('games_extracted/' . $folderName) . DIRECTORY_SEPARATOR, '', $indexPath);
    $relativePath = str_replace('\\', '/', $relativePath); // agar URL pakai slash /

    return response()->json([
        'success' => true,
        'url' => asset('games_extracted/' . $folderName . '/' . $relativePath)
    ]);
}

private function findIndexHtml($folder)
{
    $allFiles = File::allFiles($folder);

    foreach ($allFiles as $file) {
        if (basename($file) === 'index.html') {
            return $file->getPathname();
        }
    }
    return null;
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
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/' . $folder, $filename);
        return $filename;
    }

  private function extractGameZip($produk, $filename)
{
    $zipPath = storage_path('app/public/games_produk/' . $filename);
    $folderName = null;

    $zip = new \ZipArchive();
    if ($zip->open($zipPath) === TRUE) {
        $rootFolders = [];

        // Ambil nama folder utama dari dalam ZIP
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $fileName = $stat['name'];

            $parts = explode('/', $fileName);
            if (count($parts) > 1) {
                $rootFolders[] = $parts[0];
            }
        }

        $rootFolders = array_unique($rootFolders);

        // Tentukan nama folder hasil ekstrak
        if (count($rootFolders) === 1) {
            $folderName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $rootFolders[0]);
        } else {
            $folderName = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($filename, PATHINFO_FILENAME));
        }

        $extractPath = public_path('games_extracted/' . $folderName);

        // Hapus folder lama jika ada
        if ($produk->nama_folder && File::exists(public_path('games_extracted/' . $produk->nama_folder))) {
            File::deleteDirectory(public_path('games_extracted/' . $produk->nama_folder));
        }

        if (File::exists($extractPath)) {
            File::deleteDirectory($extractPath);
        }

        File::makeDirectory($extractPath, 0755, true);

        // Ekstrak ZIP
        $success = $zip->extractTo($extractPath);

        $zip->close();

        if (!$success) {
            // Hapus jika gagal ekstrak
            File::deleteDirectory($extractPath);
            return null;
        }

        return $folderName;
    }

    return null;
}
public function ownedGames()
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    // Ambil semua transaksi user dengan status 'success'
    $transactions = $user->transaksi()
        ->where('status', 'success')
        ->get();

    $ownedGames = collect();

    foreach ($transactions as $transaksi) {
        $items = $transaksi->items;

        if (is_string($items)) {
            $items = json_decode($items, true);
        }

        if (is_array($items)) {
            foreach ($items as $id => $detailProduk) {
                // Buat object produk dengan properti id dan isi detail produk
                $produkObj = (object) array_merge(['id' => $id], $detailProduk);

                // Tambah properti image_url dengan cek file gambar ada/tidak
                $imagePath = 'public/images_produk/' . ($produkObj->image ?? '');
                if (!empty($produkObj->image) && Storage::exists($imagePath)) {
                    $produkObj->image_url = asset('storage/images_produk/' . $produkObj->image);
                } else {
                    $produkObj->image_url = asset('default-image.png');
                }

                $ownedGames->push($produkObj);
            }
        }
    }

    $ownedGames = $ownedGames->unique('id')->values();

    // Cek apakah user memiliki transaksi yang dibatalkan
    $cancelled = $user->transaksi()->where('status', 'cancelled')->exists();

    return view('user.owned_games', compact('ownedGames', 'cancelled'));
}

}
