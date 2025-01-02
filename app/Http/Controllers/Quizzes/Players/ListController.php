<?php

namespace App\Http\Controllers\Quizzes\Players;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Models\Quiz;
use Illuminate\Http\Request;

/**
 * @tags Quizzes, Players
 */
class ListController extends Controller
{
    /**
     * List Players
     *
     * Get a paginated list of all players registered for this quiz.
     * Only the quiz owner can access this endpoint.
     */
    public function __invoke(Quiz $quiz, Request $request)
    {
        $this->authorize('viewAny', [Player::class, $quiz]);

        return PlayerResource::collection(
            $quiz->players()
                ->with(['user'])
                ->paginate()
        );
    }
}
