<?php

namespace App\Http\Requests\Superadmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EndorsementRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->isSuperadmin() ?? false; }
    public function rules(): array { return ['content_type' => ['required', 'string', 'max:50'], 'fee' => ['required', 'numeric', 'min:0'], 'deadline' => ['required', 'date'], 'start_date' => ['nullable', 'date'], 'status' => ['required', Rule::in(['assigned', 'in_progress', 'content_submitted', 'content_approved', 'content_rejected', 'selesai'])], 'notes' => ['nullable', 'string', 'max:3000']]; }
}
