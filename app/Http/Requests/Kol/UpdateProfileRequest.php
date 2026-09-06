<?php

namespace App\Http\Requests\Kol;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'nickname'      => ['required', 'string', 'max:100'],
            'bio'           => ['nullable', 'string'],
            'city'          => ['nullable', 'string', 'max:100'],
            'province'      => ['nullable', 'string', 'max:100'],
            'photo'         => ['nullable', 'image', 'max:2048'], // maks 2MB
            'social_media'                   => ['sometimes', 'required', 'array', 'min:1'],
            'social_media.*.platform'        => ['required_with:social_media', 'string'],
            'social_media.*.username'        => ['required_with:social_media', 'string', 'max:255'],
            'social_media.*.profile_url'     => ['required_with:social_media', 'url'],
            'social_media.*.followers_count' => ['required_with:social_media', 'integer', 'min:0'],
            'social_media.*.engagement_rate' => ['required_with:social_media', 'numeric', 'min:0', 'max:100'],
            'rate_cards'                => ['sometimes', 'required', 'array', 'min:1'],
            'rate_cards.*.platform'     => ['required_with:rate_cards', 'string'],
            'rate_cards.*.content_type' => ['required_with:rate_cards', 'string'],
            'rate_cards.*.rate'         => ['required_with:rate_cards', 'numeric', 'min:0'],
            'bank_name'           => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'bank_account_name'   => ['nullable', 'string', 'max:255'],
        ];
    }
}
