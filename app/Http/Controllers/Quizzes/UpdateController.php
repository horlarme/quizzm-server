<?php

namespace App\Http\Controllers\Quizzes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\UpdateRequest;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;

/**
 * @tags Quizzes
 */
class UpdateController extends Controller
{
    /**
     * Update Quiz
     *
     * Update a quiz with the given id.
     */
    public function __invoke(Quiz $quiz, UpdateRequest $request)
    {
        $quiz->update($request->only(['title', 'thumbnail', 'description']));

        $quiz->tags()->sync($request->collect('tags'));

        return new QuizResource($quiz);
    }
}
