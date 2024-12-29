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

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Quiz $quiz): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Quiz $quiz): bool
    {
        return false;
    }
}
