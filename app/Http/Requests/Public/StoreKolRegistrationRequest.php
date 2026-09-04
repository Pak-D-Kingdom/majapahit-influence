<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class StoreKolRegistrationRequest extends FormRequest
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
            'email'         => ['required', 'email', 'max:255', 'unique:kol_registrations,email', 'unique:users,email'],
            'phone'         => ['required', 'string', 'max:20', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,10}$/'],
            'city'          => ['nullable', 'string', 'max:100'],
            'niches'        => ['required', 'array', 'min:1'],
            'niches.*'      => ['string', 'exists:niches,name'],
            'social_media'                   => ['required', 'array', 'min:1'],
            'social_media.*.platform'        => ['required', 'string', 'in:instagram,tiktok,youtube,twitter'],
            'social_media.*.username'        => ['required', 'string', 'max:255'],
            'social_media.*.profile_url'     => ['required', 'url', 'max:500'],
            'social_media.*.followers_count' => ['required', 'integer', 'min:0'],
            'expected_rate' => ['nullable', 'string'],
            'join_reason'   => ['required', 'string', 'min:20'],
            // 'portfolio'     => ['required', 'array', 'max:5'],
            // 'portfolio.*'   => ['file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
            'agreement'     => ['required', 'accepted'],
        ];
    }
}
