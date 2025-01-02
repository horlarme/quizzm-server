<?php

namespace App\Http\Resources;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Question
 */
class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->loadMissing('options');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'option_type' => $this->option_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'options' => $this->when(
                $this->quiz->user_id === $request->user()?->id,
                fn () => OptionResource::collection($this->options),
                fn () => OptionPublicResource::collection($this->options)
            ),
        ];
    }
}
