<?php

namespace App\Http\Controllers\Quizzes;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuizMinimalResource;
use App\Models\Quiz;
use Illuminate\Http\Request;

/**
 * @tags Quizzes
 */
class SearchController extends Controller
{
    /**
     * Search
     *
     * @unauthenticated
     */
    public function __invoke(Request $request)
    {
        return QuizMinimalResource::collection(
            Quiz::query()
                ->scopes(['selectMinimal', 'public'])
                ->paginate()
        );
    }
}
