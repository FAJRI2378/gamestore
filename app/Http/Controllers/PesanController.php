<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesanController extends Controller
{
    // Menampilkan pesan masuk untuk pengguna yang terautentikasi
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Untuk admin, tampilkan semua pesan yang diterima dan dikirim
            if ($user->hasRole('admin')) {
                // Admin dapat melihat semua pesan
                $pesans = Pesan::with(['sender', 'recipient'])->latest()->get();
            } else {
                // Pengguna biasa hanya melihat pesan masuk
                $pesans = $user->pesanMasuk()->latest()->get();
            }

            // Tandai pesan sebagai dibaca jika belum dibaca
            foreach ($pesans as $pesan) {
                if (!$pesan->dibaca) {
                    $pesan->update(['dibaca' => true]);
                }
            }

            return view('pesan.index', compact('pesans'));
        } else {
            return redirect()->route('login');
        }
    }

    // Menampilkan form untuk mengirim pesan
    public function create()
    {
        // Jika admin, ambil semua user, jika tidak hanya ambil user yang bukan admin
        $users = User::where('role', 'user')->get();  // Menampilkan hanya pengguna biasa

        return view('pesan.create', compact('users'));
    }

    // Menyimpan pesan
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'to_id' => 'required|exists:users,id',
            'isi' => 'required|string',
        ]);

        // Simpan pesan baru
        Pesan::create([
            'from_id' => Auth::id(), // ID pengguna yang sedang login
            'to_id' => $request->to_id,
            'isi' => $request->isi,
            'dibaca' => false, // Pesan belum dibaca
        ]);

        return redirect()->route('pesan.index')->with('success', 'Pesan berhasil dikirim.');
    }
}
