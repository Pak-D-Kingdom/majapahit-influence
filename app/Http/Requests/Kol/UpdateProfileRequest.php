<?php

namespace App\Http\Requests\Kol;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isKol() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nickname' => ['required', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // maks 2MB

            'social_media' => ['required', 'array', 'min:1'],
            'social_media.*.id' => ['nullable', 'integer', 'exists:kol_social_media,id'],
            'social_media.*.platform' => ['required', 'string', 'max:50'],
            'social_media.*.username' => ['required', 'string', 'max:255'],
            'social_media.*.profile_url' => ['nullable', 'url', 'max:500'],
            'social_media.*.followers_count' => ['required', 'integer', 'min:0'],
            'social_media.*.engagement_rate' => ['required', 'numeric', 'min:0', 'max:100'],

            'rate_cards' => ['nullable', 'array'],
            'rate_cards.*.id' => ['nullable', 'integer', 'exists:kol_rate_cards,id'],
            'rate_cards.*.platform' => ['required', 'string', 'max:50'],
            'rate_cards.*.content_type' => ['required', 'string', 'max:50'],
            'rate_cards.*.rate' => ['required', 'numeric', 'min:0'],

            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'bank_account_name' => ['nullable', 'string', 'max:255'],
            'npwp' => ['nullable', 'string', 'max:30'],
        ];
    }
}
