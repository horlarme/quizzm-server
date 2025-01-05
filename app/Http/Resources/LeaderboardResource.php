<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Result
 */
class LeaderboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserPublicResource($this->user),
            'total_answered' => (int) $this->getAttribute('total_answered'),
            'correct_answers' => (int) $this->getAttribute('correct_answers'),
            'score' => (int) round(((int) $this->getAttribute('correct_answers') / (int) $this->getAttribute('total_questions')) * 100, 2),
        ];
    }
}
