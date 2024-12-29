<?php

namespace App\Http\Requests\Quiz;

use App\Models\Quiz;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateRequest extends CreateRequest
{
    public function authorize(): bool
    {
        return Gate::authorize('update', $this->route('quiz'))->allowed();
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'require_registration' => 'required|boolean',
            'require_approval' => 'required_unless:require_registration,false|boolean',
            'start_type' => ['required', Rule::in(Quiz::StartTypes)],
            'start_time' => 'sometimes|date|after:+30minutes',
            'visibility' => ['required', Rule::in(Quiz::Visibilities)],
        ]);
    }
}
