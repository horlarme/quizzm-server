<?php

namespace App\Http\Requests\Quiz\Question;

use App\Models\Question;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::authorize('create', [Question::class, $this->route('quiz')])->allowed();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'option_type' => ['required', Rule::in(Question::OptionTypes)],
            'options' => 'array|required|min:4',
            'options.*.value' => 'required|string|max:255',
            'options.*.is_correct' => 'required|boolean',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $options = $this->input('options', []);

            $correctOptionsCount = collect($options)->where('is_correct', true)->count();

            if ($correctOptionsCount !== 1) {
                $validator->errors()->add('options', 'Exactly one option must be marked as correct.');
            }
        });
    }
}
