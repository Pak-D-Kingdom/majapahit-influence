<?php

namespace App\Http\Requests\Superadmin;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->isSuperadmin() ?? false; }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:3000'],
            'pic_name' => ['nullable', 'string', 'max:255'],
            'pic_title' => ['nullable', 'string', 'max:100'],
            'pic_email' => ['nullable', 'email', 'max:255'],
            'pic_phone' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:3000'],
            'is_active' => ['required', 'boolean'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
