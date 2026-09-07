<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand_name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'industry_category' => ['required', 'string', 'max:100'],
            'pic_name' => ['required', 'string', 'max:255'],
            'pic_title' => ['nullable', 'string', 'max:100'],
            'pic_email' => ['required', 'email', 'max:255'],
            'pic_phone' => ['required', 'string', 'max:30'],
            'social_media' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'service_need' => ['required', 'in:endorsement,maklon,both'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
