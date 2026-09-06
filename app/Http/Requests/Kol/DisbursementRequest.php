<?php

namespace App\Http\Requests\Kol;

use Illuminate\Foundation\Http\FormRequest;

class DisbursementRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->isKol() ?? false; }
    public function rules(): array { return ['notes' => ['nullable', 'string', 'max:2000']]; }
}
