<?php

namespace App\Http\Requests\Kol;

use Illuminate\Foundation\Http\FormRequest;

class ContentProofRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->isKol() ?? false; }
    public function rules(): array { return ['posted_at' => ['required', 'date'], 'post_url' => ['nullable', 'url', 'max:500'], 'notes' => ['nullable', 'string', 'max:3000'], 'files' => ['required', 'array', 'min:1', 'max:5'], 'files.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120']]; }
}
