<?php

namespace App\Http\Controllers\Quizzes\Results;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeaderboardResource;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function __invoke(Quiz $quiz)
    {
        $leaderboard = $quiz->results()
            ->with('user')
            ->select('user_id')
            ->addSelect(DB::raw($quiz->questions()->count().' as total_questions'))
            ->addSelect(DB::raw('COUNT(user_id) as total_answered, SUM(CASE WHEN options.is_correct THEN 1 ELSE 0 END) as correct_answers'))
            ->groupBy('user_id')
            ->orderByDesc('correct_answers')
            ->paginate(columns: []);

        return LeaderboardResource::collection($leaderboard);
    }
}
