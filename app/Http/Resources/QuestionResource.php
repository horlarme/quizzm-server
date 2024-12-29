<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Question
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
            'options' => OptionResource::collection($this->answers),
        ];
    }
}
