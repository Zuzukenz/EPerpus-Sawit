<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBorrowingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Atur sesuai policy/middleware Sanctum
    }

    public function rules(): array
    {
        return [
            'id_anggota' => ['required', 'integer', 'exists:members,id_anggota'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_buku' => ['required', 'integer', 'exists:books,id_buku'],
            'items.*.jumlah_buku' => ['required', 'integer', 'min:1'],
            'tgl_pinjam' => ['nullable', 'date', 'date_format:Y-m-d'],
            'tenggat_kembali' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:tgl_pinjam'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_anggota.required' => 'ID anggota wajib diisi.',
            'id_anggota.exists' => 'Anggota tidak terdaftar dalam sistem.',
            'items.required' => 'Minimal harus memilih satu buku.',
            'items.*.id_buku.exists' => 'Buku yang dipilih tidak valid.',
            'items.*.jumlah_buku.min' => 'Jumlah buku minimal 1.',
            'tenggat_kembali.after_or_equal' => 'Tenggat kembali harus sama atau setelah tanggal pinjam.',
        ];
    }
}