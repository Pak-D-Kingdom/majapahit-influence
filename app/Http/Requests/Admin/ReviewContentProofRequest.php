<?php

namespace App\Http\Requests\Admin;

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
            'status' => ['nullable', 'in:approved,rejected'],
            'action' => ['nullable', 'in:approve,reject,approved,rejected'],
            'notes' => ['nullable', 'string'],
            'review_notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Extra validation: ensure either status or action is provided, and notes is provided if rejected.
     */
    public function passedValidation(): void
    {
        $status = $this->input('status') ?? ($this->input('action') === 'approve' ? 'approved' : ($this->input('action') === 'reject' ? 'rejected' : $this->input('action')));
        $notes = $this->input('notes') ?? $this->input('review_notes');

        $this->merge([
            'status' => $status,
            'notes' => $notes,
        ]);
    }
}
