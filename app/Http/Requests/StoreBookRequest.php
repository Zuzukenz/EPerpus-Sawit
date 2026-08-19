<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'published_year' => 'required|digits:4',
            'stock' => 'required|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori harus dipilih',
            'category_id.exists' => 'Kategori yang dipilih tidak valid',
            'title.required' => 'Judul buku harus diisi',
            'title.string' => 'Judul buku harus berupa teks',
            'title.max' => 'Judul buku maksimal 255 karakter',
            'author.required' => 'Pengarang harus diisi',
            'author.string' => 'Pengarang harus berupa teks',
            'author.max' => 'Pengarang maksimal 255 karakter',
            'publisher.required' => 'Penerbit harus diisi',
            'publisher.string' => 'Penerbit harus berupa teks',
            'publisher.max' => 'Penerbit maksimal 255 karakter',
            'published_year.required' => 'Tahun terbit harus diisi',
            'published_year.digits' => 'Tahun terbit harus berupa 4 digit',
            'stock.required' => 'Stok harus diisi',
            'stock.integer' => 'Stok harus berupa angka',
            'stock.min' => 'Stok minimal 0',
        ];
    }
}
