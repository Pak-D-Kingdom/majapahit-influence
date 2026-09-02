<?php

namespace App\Http\Requests\Superadmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CampaignRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->isSuperadmin() ?? false; }
    public function rules(): array { return ['brand_id' => ['required', 'exists:brands,id'], 'name' => ['required', 'string', 'max:255'], 'description' => ['nullable', 'string', 'max:5000'], 'start_date' => ['nullable', 'date'], 'end_date' => ['nullable', 'date', 'after_or_equal:start_date'], 'budget' => ['required', 'numeric', 'min:0'], 'content_requirements' => ['nullable', 'string', 'max:5000'], 'dos_and_donts' => ['nullable', 'string', 'max:5000'], 'status' => ['required', Rule::in(['draft', 'aktif', 'selesai'])], 'brief' => ['nullable', 'file', 'mimes:pdf,doc,docx,png,jpg,jpeg', 'max:10240']]; }
}
