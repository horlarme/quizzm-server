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
     * Fetch a list of quizzes that match the given search criteria. The list of quiz return are only published and public quizzes.
     *
     * @unauthenticated
     */
    public function __invoke(Request $request)
    {
        return QuizMinimalResource::collection(
            Quiz::query()
                ->with('user')
                ->withCount('questions')
                ->scopes(['selectMinimal', 'public', 'published'])
                ->paginate()
        );
    }
}
