<?php

namespace App\Http\Requests\Quiz\Result;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class AnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::authorize('answer', $this->route('question'))->allowed();
    }

    public function rules(): array
    {
        return [
            'option_id' => ['required', 'string', 'exists:options,id'],
        ];
    }
}
