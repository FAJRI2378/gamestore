<?php

namespace App\Http\Middleware;

// app/Http/Middleware/CheckGameOwner.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Game;

class CheckGameOwner
{
    public function handle(Request $request, Closure $next)
    {
        $gameId = $request->route('game'); // asumsikan route model binding bernama 'game'
        $game = Game::find($gameId);

        if (!$game || $game->user_id !== auth()->id()) {
            abort(403, 'Anda tidak punya akses ke game ini.');
        }

        return $next($request);
    }
}
