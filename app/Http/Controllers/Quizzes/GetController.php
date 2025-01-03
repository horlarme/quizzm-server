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
class GetController extends Controller
{
    /**
     * Get Quiz
     *
     * This endpoint retrieves a single quiz by its ID.
     * For draft quizzes, the user must be the owner of the quiz.
     *
     * @throws AuthorizationException
     *
     * @unauthenticated
     */
    public function __invoke(Quiz $quiz, Request $request)
    {
        $quiz->load(['user', 'tags', 'questions.options']);

        $this->authorize('view', $quiz);

        return new QuizResource($quiz);
    }
}
