<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreKolManualRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name'     => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email'],
            'nickname'      => ['required', 'string', 'max:100'],
            'bio'           => ['nullable', 'string'],
            'city'          => ['nullable', 'string', 'max:100'],
            'province'      => ['nullable', 'string', 'max:100'],
            'tier_id'       => ['nullable', 'exists:tiers,id'],
            'photo'         => ['nullable', 'image', 'max:2048'], // maks 2MB
            'social_media'                   => ['required', 'array', 'min:1'],
            'social_media.*.platform'        => ['required', 'string'],
            'social_media.*.username'        => ['required', 'string', 'max:255'],
            'social_media.*.profile_url'     => ['required', 'url'],
            'social_media.*.followers_count' => ['required', 'integer', 'min:0'],
            'social_media.*.engagement_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'rate_cards'                => ['required', 'array', 'min:1'],
            'rate_cards.*.platform'     => ['required', 'string'],
            'rate_cards.*.content_type' => ['required', 'string'],
            'rate_cards.*.rate'         => ['required', 'numeric', 'min:0'],
            'bank_name'           => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'bank_account_name'   => ['nullable', 'string', 'max:255'],
        ];
    }
}
