<?php

namespace App\Http\Controllers\Quizzes\Questions;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Traits\Conditionable;

/**
 * @tags Quizzes, Results
 */
class AnswerController extends Controller
{
    use Conditionable;

    /**
     * Submit Answer
     *
     * Submit an answer for the current question and get the next question if available.
     */
    public function __invoke(
        Quiz $quiz,
        Question $question,
        Option $option,
        Request $request
    ) {
        $this->authorize('answer', [$question, $option]);

        Result::query()->create([
            'user_id' => $request->user()->id,
            'option_id' => $option->id,
        ]);

        $nextQuestion = $quiz->nextQuestionForUser($request->user());

        return response()->json([
            'question' => $nextQuestion ? new QuestionResource($nextQuestion) : null,
        ]);
    }
}
