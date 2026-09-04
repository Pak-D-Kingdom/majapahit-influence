<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'brand_id' => ['required', 'exists:brands,id'],
            'description' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'content_requirements' => ['nullable', 'string'],
            'dos_and_donts' => ['nullable', 'string'],
            'status' => ['nullable', 'in:draft,aktif,selesai'],
            'brief_files' => ['nullable', 'array', 'max:5'],
            'brief_files.*' => ['file', 'mimes:pdf,doc,docx,jpg,jpeg,png,zip', 'max:10240'],
        ];
    }
}
