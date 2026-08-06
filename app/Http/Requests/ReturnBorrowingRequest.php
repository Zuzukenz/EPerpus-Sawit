<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnBorrowingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tgl_kembali' => ['required', 'date', 'date_format:Y-m-d'],
        ];
    }

    public function messages(): array
    {
        return [
            'tgl_kembali.required' => 'Tanggal pengembalian wajib diisi.',
            'tgl_kembali.date' => 'Format tanggal pengembalian tidak valid.',
        ];
    }
}