<?php

namespace App\Http\Resources;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Quiz
 */
class QuizMinimalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->loadMissing(['user', 'tags']);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'thumbnail' => $this->thumbnail,
            'description' => $this->description,
            'visibility' => $this->visibility,
            'questions_count' => $this->questions_count,
            'user' => new UserPublicResource($this->user),
            'created_at' => $this->created_at,
            'tags' => TagResource::collection($this->tags),
        ];
    }
}
