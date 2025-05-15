<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamePolicy
{
    use HandlesAuthorization;

    // Jika admin, boleh akses semua
    public function before(User $user, $ability)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }

    // User hanya bisa update game yang dia buat
    public function update(User $user, Game $game)
    {
        return $user->id === $game->user_id;
    }

    // User hanya bisa delete game yang dia buat
    public function delete(User $user, Game $game)
    {
        return $user->id === $game->user_id;
    }

    // User bisa lihat game (boleh semua user)
    public function view(User $user, Game $game)
    {
        return true;
    }

    // Optional: user bisa membuat game
    public function create(User $user)
    {
        return true;
    }
}
