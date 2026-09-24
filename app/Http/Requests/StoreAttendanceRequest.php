<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Strict authorization: Hanya karyawan yang diizinkan melakukan endpoint ini
        return $this->user() !== null && $this->user()->role === 'karyawan';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'qr_token' => ['required', 'string', 'min:64', 'max:64', 'regex:/^[a-zA-Z0-9]+$/'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'qr_token.required' => 'Token QR tidak boleh kosong.',
            'qr_token.regex'    => 'Format token QR terindikasi anomali.',
            'qr_token.min'      => 'Integritas token QR tidak valid.',
            'qr_token.max'      => 'Integritas token QR tidak valid.',
        ];
    }
}