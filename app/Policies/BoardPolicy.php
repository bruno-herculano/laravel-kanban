<?php
namespace App\Policies;

use App\Models\Board;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BoardPolicy
{
    public function view(User $user, Board $board)
    {
        return $user->id === $board->user_id
            ? Response::allow()
            : Response::deny('Você não tem acesso a este quadro.');
    }

    public function update(User $user, Board $board)
    {
        return $user->id === $board->user_id;
    }

    public function delete(User $user, Board $board)
    {
        return $user->id === $board->user_id;
    }
}
