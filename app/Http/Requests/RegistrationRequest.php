<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'phone' => ['required', 'regex:/^(08|62)[0-9+\- ]{8,17}$/'],
            'city' => ['nullable', 'string', 'max:100'],
            'niches' => ['required', 'array', 'min:1'],
            'niches.*' => ['string', 'max:100'],
            'expected_rate' => ['nullable', 'string', 'max:1000'],
            'join_reason' => ['nullable', 'string', 'max:3000'],
            'terms' => ['accepted'],
            'portfolio' => ['nullable', 'array', 'max:5'],
            'portfolio.*' => ['file', 'mimes:jpg,jpeg,png,pdf,mp4', 'max:10240'],
        ];

        if ($this->has('social_media') && is_array($this->input('social_media'))) {
            $rules['social_media'] = ['required', 'array', 'min:1'];
            $rules['social_media.*.username'] = ['nullable', 'string', 'max:255'];
            $rules['social_media.*.profile_url'] = ['nullable', 'url', 'max:500'];
            $rules['social_media.*.followers_count'] = ['nullable', 'integer', 'min:0'];
            $rules['platforms'] = ['nullable', 'array'];
        } else {
            $rules['platform'] = ['required', 'string', 'max:50'];
            $rules['username'] = ['required', 'string', 'max:255'];
            $rules['profile_url'] = ['nullable', 'url', 'max:500'];
            $rules['followers_count'] = ['required', 'integer', 'min:0'];
        }

        return $rules;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->has('social_media') && is_array($this->input('social_media'))) {
                $hasValidAccount = false;
                $platforms = (array) $this->input('platforms', array_keys($this->input('social_media')));
                foreach ($this->input('social_media') as $platform => $account) {
                    if (is_array($account) && ! empty($account['username']) && (empty($this->input('platforms')) || in_array($platform, $platforms))) {
                        $hasValidAccount = true;
                        if (! isset($account['followers_count']) || $account['followers_count'] === '' || (int) $account['followers_count'] < 0) {
                            $validator->errors()->add("social_media.{$platform}.followers_count", "Jumlah followers untuk {$platform} wajib diisi.");
                        }
                    }
                }
                if (! $hasValidAccount) {
                    $validator->errors()->add('platforms', 'Pilih minimal satu platform media sosial dan isi username serta jumlah followers Anda.');
                }
            }
        });
    }
}
