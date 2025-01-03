<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class QuestionPolicy
{
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

    public function answer(User $user, Question $question)
    {
        Gate::authorize('play', $question->quiz);

        // todo: confirm selected option belongs to the question
    }
}
