<?php

namespace App\Http\Requests\Superadmin;

use App\Models\KolProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KolProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isSuperadmin() ?? false;
    }

    public function rules(): array
    {
        $profile = $this->route('kol');
        $userId = $profile instanceof KolProfile ? $profile->user_id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => [$userId ? 'nullable' : 'required', 'string', 'min:8'],
            'nickname' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'tier_id' => ['nullable', 'integer', 'exists:tiers,id'],
            'status' => ['required', Rule::in(['pending', 'aktif', 'nonaktif', 'blacklist'])],
            'platform' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:255'],
            'profile_url' => ['nullable', 'url', 'max:500'],
            'followers_count' => ['required', 'integer', 'min:0'],
            'engagement_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'niches' => ['nullable', 'array'],
            'niches.*' => ['integer', 'exists:niches,id'],
        ];
    }
}
