<?php

namespace App\Http\Requests\Superadmin;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->isSuperadmin() ?? false; }
    public function rules(): array { return ['kol_profile_id' => ['required', 'exists:kol_profiles,id'], 'content_type' => ['required', 'string', 'max:50'], 'fee' => ['required', 'numeric', 'min:0'], 'deadline' => ['required', 'date'], 'start_date' => ['nullable', 'date'], 'notes' => ['nullable', 'string', 'max:3000']]; }
}
