<?php

namespace App\Http\Requests\Superadmin;

use Illuminate\Foundation\Http\FormRequest;

class ContentProofReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isSuperadmin() ?? true;
    }

    protected function prepareForValidation(): void
    {
        $action = $this->input('action') ?? $this->input('status');
        if ($action === 'approved') {
            $action = 'approve';
        } elseif ($action === 'rejected') {
            $action = 'reject';
        }

        $reviewNotes = $this->input('review_notes') ?? $this->input('notes');

        $this->merge([
            'action' => $action,
            'review_notes' => $reviewNotes,
        ]);
    }

    public function rules(): array
    {
        return [
            'action' => ['required', 'in:approve,reject'],
            'review_notes' => ['required_if:action,reject', 'nullable', 'string', 'max:3000'],
        ];
    }
}
