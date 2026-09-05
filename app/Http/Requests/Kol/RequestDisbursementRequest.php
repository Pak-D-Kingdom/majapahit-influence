<?php

namespace App\Http\Requests\Kol;

use Illuminate\Foundation\Http\FormRequest;

class RequestDisbursementRequest extends FormRequest
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
            'commission_ids' => ['required', 'array', 'min:1'],
            'commission_ids.*' => ['required', 'integer', 'exists:commissions,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'commission_ids.required' => 'Pilih minimal satu komisi untuk diajukan pencairan.',
            'commission_ids.min' => 'Pilih minimal satu komisi untuk diajukan pencairan.',
        ];
    }
}
