<?php

namespace App\Http\Requests\Endorsement;

use Illuminate\Foundation\Http\FormRequest;

class ReviewContentProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', 'in:approve,reject'],
            'review_notes' => ['required_if:action,reject', 'nullable', 'string'],
        ];
    }
}
