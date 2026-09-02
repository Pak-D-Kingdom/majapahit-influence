<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'regex:/^(08|62)[0-9+\- ]{8,17}$/'],
            'city' => ['nullable', 'string', 'max:100'],
            'niches' => ['required', 'array', 'min:1'],
            'niches.*' => ['string', 'max:100'],
            'platform' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:255'],
            'profile_url' => ['nullable', 'url', 'max:500'],
            'followers_count' => ['required', 'integer', 'min:0'],
            'expected_rate' => ['nullable', 'string', 'max:1000'],
            'join_reason' => ['nullable', 'string', 'max:3000'],
            'terms' => ['accepted'],
            'portfolio.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }
}
