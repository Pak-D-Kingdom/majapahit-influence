<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ApproveDisbursementRequest extends FormRequest
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
            'status' => ['required', 'string', 'in:approved,rejected'],
            'notes' => ['nullable', 'string', 'max:1000'],
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
            'commission_ids.required' => 'Pilih minimal satu komisi untuk diproses.',
            'status.required' => 'Status persetujuan wajib dipilih.',
            'status.in' => 'Status persetujuan harus approved atau rejected.',
        ];
    }
}
