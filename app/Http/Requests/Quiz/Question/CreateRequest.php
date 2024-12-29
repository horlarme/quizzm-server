<?php

namespace App\Http\Requests\Quiz\Question;

use App\Models\Question;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::authorize('create', [Question::class, $this->route('quiz')])->allowed();
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'option_type' => ['required', Rule::in(Question::OptionTypes)],
            'options' => 'array|required|min:4',
            'options.*.value' => ['required', 'string', 'max:255'],
            'options.*.is_correct' => 'required|boolean',
        ];

        if ($this->get('option_type') === Question::OptionTypeImage) {
            $rules['options.*.value'][] = 'url';
        }

        return $rules;
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $correctOptionsCount = $this->collect('options', [])->where('is_correct', true)->count();

            $validator->errors()->addIf($correctOptionsCount !== 1, 'options', 'Exactly one option must be marked as correct.');
        });
    }
}
