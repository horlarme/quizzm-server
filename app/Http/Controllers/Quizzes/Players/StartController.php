<?php

namespace App\Http\Controllers\Quizzes\Players;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Player;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

/**
 * @tags Quizzes, Players
 */
class StartController extends Controller
{
    /**
     * Start Quiz
     *
     * Start a quiz and get the first/next question.
     * If the user has already started the quiz, returns the next unanswered question.
     *
     * @throws AuthorizationException
     */
    public function __invoke(Quiz $quiz, Request $request)
    {
        $this->authorize('start', [Player::class, $quiz]);

        $nextQuestion = $quiz->nextQuestionForUser($request->user());

        abort_if(! $nextQuestion, 404, 'No more questions available.');

        return response()->json([
            'total' => (int) $quiz->questions()->count(),
            'answered' => (int) $quiz->results()->whereBelongsTo($request->user())->count(),
            'question' => new QuestionResource($nextQuestion),
        ]);
    }
}
