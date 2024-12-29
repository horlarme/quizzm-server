<?php

namespace App\Http\Controllers\Quizzes\Questions;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

/**
 * @tags Quizzes, Questions
 */
class DeleteController extends Controller
{
    /**
     * Delete Question
     *
     * This endpoint allows you to delete a question.
     *
     * @throws AuthorizationException
     */
    public function __invoke(Quiz $quiz, Question $question, Request $request)
    {
        $this->authorize('delete', $question);

        $question->delete();

        return response()->noContent();
    }
}
