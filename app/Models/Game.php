<?phpw

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game; // Pastikan kamu punya model Game, kalau belum, nanti buat juga

class GameController extends Controller
{
    public function store()
    {
        // Ambil semua game, bisa kamu sesuaikan querynya nanti
        $games = Game::all();

        return view('game.store', compact('games'));
    }
}
