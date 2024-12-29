<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuestionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Question $question): bool
    {
        return false;
    }

    public function create(User $user, Quiz $quiz): Response
    {
        if (! $quiz->isDraft()) {
            return Response::deny('You cannot add questions to a published quiz.');
        }

        if ($quiz->user_id !== $user->id) {
            return Response::deny('You cannot add questions to a quiz that is not yours.');
        }

        return Response::allow();
    }

    public function update(User $user, Question $question): Response
    {
        if (! $question->quiz->isDraft()) {
            return Response::deny('You cannot update a question in a '.$question->quiz->status.' quiz.');
        }

        if ($question->quiz->user_id !== $user->id) {
            return Response::deny('You cannot update a question that is not yours.');
        }

        return Response::allow();
    }

    public function delete(User $user, Question $question): Response
    {
        if ($question->quiz->user_id !== $user->id) {
            return Response::deny('You cannot delete a question that is not yours.');
        }

        if (! $question->quiz->isDraft()) {
            return Response::deny('You cannot delete a question from a published quiz.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Question $question): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Question $question): bool
    {
        return false;
    }
}
