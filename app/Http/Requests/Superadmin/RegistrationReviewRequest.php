<?php

namespace App\Http\Requests\Superadmin;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationReviewRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->isSuperadmin() ?? false; }

    public function rules(): array
    {
        return ['action' => ['required', 'in:approve,reject'], 'notes' => ['nullable', 'string', 'max:3000'], 'rejection_reason' => ['required_if:action,reject', 'nullable', 'string', 'max:3000']];
    }
}
