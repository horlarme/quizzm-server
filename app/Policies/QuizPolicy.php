<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuizPolicy
{
    public function view(User $user, Quiz $quiz): bool
    {
        return false;
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

    public function forceDelete(User $user, Quiz $quiz): bool
    {
        return false;
    }
}
