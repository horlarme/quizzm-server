<?php

namespace App\Http\Controllers\Quizzes;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuizMinimalResource;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

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
     *
     * @return AnonymousResourceCollection<LengthAwarePaginator<QuizMinimalResource>>
     */
    public function __invoke(Request $request)
    {
        return QuizMinimalResource::collection(
            Quiz::query()
                ->with(['tags', 'user'])
                ->withCount('questions')
                ->scopes(['selectMinimal', 'public', 'published'])
                ->paginate(page: $request->integer('page', 1))
        );
    }
}
