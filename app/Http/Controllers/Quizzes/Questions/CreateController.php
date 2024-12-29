<?php

namespace App\Http\Controllers\Quizzes\Questions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\Question\CreateRequest;
use App\Http\Resources\QuizResource;
use App\Models\Question;
use App\Models\Quiz;

/**
 * @tags Quizzes,Questions
 */
class CreateController extends Controller
{
    /**
     * Create Question
     *
     * Create a new question for a quiz.
     *
     * @return QuizResource
     */
    public function __invoke(Quiz $quiz, CreateRequest $request)
    {
        /** @var Question $question */
        $question = $quiz->questions()->create([
            'title' => $request->get('title'),
            'option_type' => $request->get('option_type'),
        ]);

        $question->options()->createMany($request->get('options'));

        return new QuizResource($quiz->fresh(['user', 'questions.options']));
    }
}
