<?php

namespace App\Http\Resources;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Quiz
 */
class QuizResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->loadMissing('questions.options');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'require_registration' => (bool) $this->require_registration,
            'require_approval' => (bool) $this->require_approval,
            'start_type' => $this->start_type,
            'status' => $this->status ?? Quiz::StatusDraft,
            'start_time' => $this->start_time,
            'visibility' => $this->visibility,

            'questions_count' => count($this->questions),
            'questions' => $this->when($request->user()?->id === $this->user_id, fn () => QuestionResource::collection($this->questions)),
            'user' => new UserPublicResource($this->user),
        ];
    }
}
