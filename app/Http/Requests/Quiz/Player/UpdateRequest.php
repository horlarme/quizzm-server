<?php

namespace App\Http\Requests\Quiz\Player;

use App\Models\Player;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::authorize('update', $this->route('player'))->allowed();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in([Player::StatusApproved, Player::StatusRejected])],
        ];
    }
}
