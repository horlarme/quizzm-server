<?php

namespace App\Http\Controllers\Quizzes\Players;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\Player\UpdateRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Models\Quiz;

/**
 * @tags Quizzes, Players
 */
class UpdateController extends Controller
{
    /**
     * Update Player
     *
     * Update a player's registration status.
     * Only the quiz owner can update pending players.
     */
    public function __invoke(Quiz $quiz, Player $player, UpdateRequest $request)
    {
        $player->update($request->validated());

        return new PlayerResource($player->load('user'));
    }
}
