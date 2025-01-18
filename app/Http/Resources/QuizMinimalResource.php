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
            'visibility' => $this->visibility,
            'user' => new UserPublicResource($this->user),
            'created_at' => $this->created_at,
            'published_at' => $this->published_at,
            'start_time' => $this->start_time,
            'start_type' => $this->start_type,
            'require_registration' => $this->require_registration,
            'require_approval' => $this->require_approval,
            'tags' => TagResource::collection($this->tags),
        ];
    }
}
