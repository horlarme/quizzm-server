<?php

namespace App\Http\Requests\Quiz\Question;

use Illuminate\Support\Facades\Gate;

class UpdateRequest extends CreateRequest
{
    public function authorize(): bool
    {
        return Gate::authorize('update', $this->route('question'))->allowed();
    }
}
