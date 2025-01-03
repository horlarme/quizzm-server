<?php

namespace App\Http\Controllers\Quizzes;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

/**
 * @tags Quizzes
 */
class PublishController extends Controller
{
    /**
     * Publish
     *
     * Publish a quiz. Quiz will be available to other users.
     *
     * @throws AuthorizationException
     */
    public function __invoke(Quiz $quiz, Request $request)
    {
        $quiz->loadMissing(['user', 'tags', 'questions.options']);

        $this->authorize('publish', $quiz);

        $quiz->update([
            'status' => Quiz::StatusPublished,
            'published_at' => now(),
        ]);

        return new QuizResource($quiz);
    }
}
