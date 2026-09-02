<?php

namespace App\Http\Requests\Endorsement;

use Illuminate\Foundation\Http\FormRequest;

class SubmitContentProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'posted_at' => ['required', 'date', 'before_or_equal:today'],
            'post_url' => ['required', 'url', 'max:500'],
            'notes' => ['nullable', 'string'],
            'proof_files' => ['required', 'array', 'min:1', 'max:5'],
            'proof_files.*' => ['file', 'mimes:jpg,jpeg,png,webp,pdf,mp4', 'max:10240'],
        ];
    }
}
