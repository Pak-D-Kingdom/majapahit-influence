<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProcessDisbursementRequest extends FormRequest
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
            'transfer_date' => ['required', 'date'],
            'transfer_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
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
            'transfer_date.required' => 'Tanggal transfer wajib diisi.',
            'transfer_date.date' => 'Format tanggal transfer tidak valid.',
            'transfer_proof.required' => 'Bukti transfer wajib diunggah.',
            'transfer_proof.file' => 'Bukti transfer harus berupa file.',
            'transfer_proof.mimes' => 'Format bukti transfer harus berupa JPG, JPEG, PNG, WEBP, atau PDF.',
            'transfer_proof.max' => 'Ukuran file bukti transfer maksimal 5MB.',
        ];
    }
}
