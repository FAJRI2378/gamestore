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
            // Gunakan nama folder internal ZIP
            $folderName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $rootFolders[0]);
        } else {
            // Gunakan nama file ZIP tanpa ekstensi
            $folderName = pathinfo($produk->game, PATHINFO_FILENAME);
            $folderName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $folderName);
        }
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Gagal membuka file ZIP.'
        ]);
    }

    $indexPath = public_path('games_extracted/' . $folderName . '/index.html');

    // Jika belum diekstrak, bisa ekstrak otomatis (opsional)
    if (!File::exists($indexPath)) {
        $extractPath = public_path('games_extracted/' . $folderName);

        $zip = new \ZipArchive();
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
        }

        // Cek ulang setelah ekstrak
        if (!File::exists($indexPath)) {
            return response()->json([
                'success' => false,
                'message' => 'File index.html tidak ditemukan setelah ekstrak.'
            ]);
        }
    }

    return response()->json([
        'success' => true,
        'url' => asset('games_extracted/' . $folderName . '/index.html')
    ]);
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

        // Tentukan folderName
        if (count($rootFolders) === 1) {
            // Kalau di dalam ZIP sudah ada folder tunggal
            $folderName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $rootFolders[0]);
        } else {
            // Kalau flat atau banyak folder
            $folderName = pathinfo($filename, PATHINFO_FILENAME);
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

        // Kalau isi ZIP hanya 1 folder, ekstrak ke games_extracted langsung
        if (count($rootFolders) === 1) {
            // Ekstrak ke games_extracted/
            $zip->extractTo(public_path('games_extracted'));
        } else {
            // Ekstrak ke dalam folder tertentu
            $zip->extractTo($extractPath);
        }

        $zip->close();
        return $folderName;
    }
}

}
