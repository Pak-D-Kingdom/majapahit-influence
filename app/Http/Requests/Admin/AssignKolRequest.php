<?php

namespace App\Http\Requests\Admin;

use App\Models\KolProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class AssignKolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kol_profile_id' => ['required', 'exists:kol_profiles,id'],
            'content_type' => ['required', 'string', 'max:50'],
            'fee' => ['required', 'numeric', 'min:0'],
            'deadline' => ['required', 'date'],
            'start_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Extra validation according to Business Rule 2 & 3: KOL must be 'aktif'.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->kol_profile_id) {
                    $kol = KolProfile::find($this->kol_profile_id);
                    if ($kol && $kol->status !== 'aktif') {
                        $validator->errors()->add(
                            'kol_profile_id',
                            "KOL dengan status '{$kol->status}' tidak dapat ditugaskan endorsement baru. Hanya KOL aktif yang dapat di-assign (BR2 & BR3)."
                        );
                    }
                }
            },
        ];
    }
}
