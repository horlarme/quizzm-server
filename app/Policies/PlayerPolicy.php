<?php

namespace App\Policies;

use App\Models\Player;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PlayerPolicy
{
    public function register(User $user, Quiz $quiz): Response
    {
        if ($quiz->user_id === $user->id) {
            return Response::deny('You cannot register for your own quiz.');
        }

        if (! $quiz->isPublished()) {
            return Response::deny('This quiz is not published yet.');
        }

        if ($quiz->require_registration) {
            $existingRequest = $quiz->players()->where('user_id', $user->id)->exists();
            if ($existingRequest) {
                return Response::deny('You have already registered for this quiz.');
            }
        }

        return Response::allow();
    }

    public function update(User $user, Player $player): Response
    {
        if (! $player->quiz->require_registration) {
            return Response::deny('This quiz does not require registration approval.');
        }

        if ($user->id !== $player->quiz->user_id) {
            return Response::deny('Only the quiz owner can update player status.');
        }

        if ($player->status !== Player::StatusPending) {
            return Response::deny('Only pending registrations can be updated.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can view list of players.
     */
    public function viewAny(User $user, Quiz $quiz): Response
    {
        if ($user->id !== $quiz->user_id) {
            return Response::deny('Only quiz owner can view players.');
        }

        return Response::allow();
    }
}
