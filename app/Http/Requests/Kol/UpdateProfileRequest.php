<?php

namespace App\Http\Requests\Kol;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
<<<<<<< HEAD
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
=======
    public function authorize(): bool
    {
        return $this->user()?->isKol() ?? false;
    }

    /**
     * @return array<string, array<int, string>>
>>>>>>> origin/chanan
     */
    public function rules(): array
    {
        return [
<<<<<<< HEAD
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
=======
            'nickname' => ['required', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

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

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nickname.required' => 'Nama panggilan wajib diisi.',
            'social_media.required' => 'Minimal 1 akun sosial media harus diisi.',
            'social_media.min' => 'Minimal 1 akun sosial media harus diisi.',
            'social_media.*.platform.required' => 'Platform sosial media wajib dipilih.',
            'social_media.*.username.required' => 'Username sosial media wajib diisi.',
            'social_media.*.followers_count.required' => 'Jumlah followers wajib diisi.',
            'social_media.*.engagement_rate.required' => 'Engagement rate wajib diisi.',
            'rate_cards.*.platform.required' => 'Platform rate card wajib dipilih.',
            'rate_cards.*.content_type.required' => 'Tipe konten rate card wajib dipilih.',
            'rate_cards.*.rate.required' => 'Tarif rate card wajib diisi.',
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.mimes' => 'Format foto profil harus JPG, JPEG, atau PNG.',
            'photo.max' => 'Ukuran foto profil maksimal 2MB.',
>>>>>>> origin/chanan
        ];
    }
}
