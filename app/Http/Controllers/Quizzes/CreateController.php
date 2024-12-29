<?php

namespace App\Http\Controllers\Quizzes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\CreateRequest;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;

/**
 * @tags Quizzes
 */
class CreateController extends Controller
{
    /**
     * Create New Quiz
     *
     * Create a new draft quiz.
     */
    public function __invoke(CreateRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return new QuizResource($user->quizzes()->create($request->validated()));
    }
}
