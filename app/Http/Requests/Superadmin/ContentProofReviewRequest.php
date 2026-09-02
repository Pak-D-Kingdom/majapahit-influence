<?php

namespace App\Http\Requests\Superadmin;

use Illuminate\Foundation\Http\FormRequest;

class ContentProofReviewRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->isSuperadmin() ?? false; }
    public function rules(): array { return ['action' => ['required', 'in:approve,reject'], 'review_notes' => ['required_if:action,reject', 'nullable', 'string', 'max:3000']]; }
}
