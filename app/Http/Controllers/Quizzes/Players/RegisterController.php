<?php

namespace App\Http\Controllers\Quizzes\Players;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Models\Quiz;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

/**
 * @tags Quizzes, Player
 */
class RegisterController extends Controller
{
    /**
     * Quiz Registration
     *
     * Register a player for a quiz.
     *
     * @throws AuthorizationException
     */
    public function __invoke(Quiz $quiz, Request $request)
    {
        $this->authorize('register', [Player::class, $quiz]);

        $player = $quiz->players()->create([
            'user_id' => $request->user()->id,
            'status' => $quiz->require_approval ? Player::StatusPending : Player::StatusApproved,
        ]);

        return new PlayerResource($player);
    }
}
