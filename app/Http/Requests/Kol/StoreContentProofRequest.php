<?php

namespace App\Http\Requests\Kol;

use Illuminate\Foundation\Http\FormRequest;

class StoreContentProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'post_url' => ['required', 'url', 'max:500'],
            'post_date' => ['nullable', 'date'],
            'posted_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'proof_files' => ['nullable', 'array', 'max:5'],
            'proof_files.*' => ['file', 'mimes:jpg,jpeg,png,webp,pdf,mp4', 'max:5120'],
        ];
    }

    /**
     * Prepare data for validation / formatting.
     */
    public function passedValidation(): void
    {
        if ($this->has('post_date') && !$this->has('posted_at')) {
            $this->merge(['posted_at' => $this->input('post_date')]);
        }
    }
}
