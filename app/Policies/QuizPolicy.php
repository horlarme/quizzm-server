<?php

namespace App\Policies;

use App\Models\Player;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuizPolicy
{
    public function view(?User $user, Quiz $quiz): Response
    {
        if ($quiz->isDraft() && $user?->id !== $quiz->user_id) {
            return Response::deny('You are not authorized to view this quiz. Only the quiz owner can view a draft quiz.');
        }

        return Response::allow();
    }

    public function update(User $user, Quiz $quiz): Response
    {
        if ($user->id !== $quiz->user_id) {
            return Response::deny('You are not authorized to update this quiz.');
        }

        if (! $quiz->isDraft()) {
            return Response::deny('You cannot update a published quiz.');
        }

        return Response::allow();
    }

    public function delete(User $user, Quiz $quiz): bool
    {
        return false;
    }

    public function publish(User $user, Quiz $quiz): Response
    {
        if ($user->id !== $quiz->user_id) {
            return Response::deny('You are not authorized to publish this quiz.');
        }

        if (! $quiz->isDraft()) {
            return Response::deny('This quiz is already published.');
        }

        if ($quiz->questions->count() < 2) {
            return Response::deny('You need at least 2 questions to publish the quiz.');
        }

        if ($quiz->start_time && $quiz->start_time->lessThan(now()->addMinutes(30))) {
            return Response::deny('You cannot publish a quiz within 30 minutes of starting.');
        }

        return Response::allow();
    }

    public function restore(User $user, Quiz $quiz): bool
    {
        return false;
    }

    public function play(User $user, Quiz $quiz): Response
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

    public function forceDelete(User $user, Quiz $quiz): bool
    {
        return false;
    }
}
