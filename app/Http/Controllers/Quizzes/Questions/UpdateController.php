<?php

namespace App\Http\Controllers\Quizzes\Questions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\Question\UpdateRequest;
use App\Http\Resources\QuizResource;
use App\Models\Question;
use App\Models\Quiz;

/**
 * @tags Quizzes, Questions
 */
class UpdateController extends Controller
{
    /**
     * Update Question
     *
     * Update a question in a quiz.
     */
    public function __invoke(Quiz $quiz, Question $question, UpdateRequest $request): QuizResource
    {
        $question->update([
            'title' => $request->get('title'),
            'option_type' => $request->get('option_type'),
        ]);

        $question->options()->delete();

        $question->options()->createMany($request->get('options'));

        return new QuizResource($quiz->fresh(['user', 'questions.options']));
    }
}
