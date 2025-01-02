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

    public function start(User $user, Quiz $quiz): Response
    {
        if (! $quiz->isPublished()) {
            return Response::deny('This quiz is not published yet.');
        }

        if ($quiz->require_registration) {
            $player = $quiz->players()->where('user_id', $user->id)->first();

            if (! $player) {
                return Response::deny('You need to register for this quiz first.');
            }

            if ($player->status !== Player::StatusApproved) {
                return Response::deny('Your registration has not been approved yet.');
            }
        }

        if ($quiz->start_type === Quiz::StartTypeAutomatic && $quiz->start_time?->isFuture()) {
            return Response::deny('This quiz has not started yet.');
        }

        if ($quiz->start_type === Quiz::StartTypeManual && ! $quiz->started_at) {
            return Response::deny('This quiz has not been started by the owner yet.');
        }

        if ($quiz->start_type === Quiz::StartTypeUser && $quiz->start_time?->isFuture()) {
            return Response::deny('This quiz is scheduled to start in the future.');
        }

        return Response::allow();
    }

    public function approve(User $user, Player $player): Response
    {
        if ($user->id !== $player->quiz->user_id) {
            return Response::deny('Only the quiz owner can approve registrations.');
        }

        if ($player->status !== Player::StatusPending) {
            return Response::deny('This registration is not pending.');
        }

        return Response::allow();
    }
}
