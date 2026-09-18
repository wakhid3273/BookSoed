<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'book_ids'   => ['required', 'array', 'min:1'],
            'book_ids.*' => ['required', 'integer', 'exists:books,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'book_ids.required'   => 'Pilih minimal satu buku untuk dipesan.',
            'book_ids.array'      => 'Format data buku tidak valid.',
            'book_ids.min'        => 'Pilih minimal satu buku.',
            'book_ids.*.exists'   => 'Salah satu buku tidak ditemukan.',
        ];
    }
}
