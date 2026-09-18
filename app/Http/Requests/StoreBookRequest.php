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
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'title'       => ['required', 'string', 'max:255'],
            'author'      => ['required', 'string', 'max:255'],
            'isbn'        => ['nullable', 'string', 'max:20'],
            'condition'   => ['required', 'in:NEW,LIKE_NEW,GOOD,FAIR,POOR'],
            'price'       => ['required', 'numeric', 'min:0'],
            'photo'       => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists'   => 'Kategori tidak valid.',
            'title.required'       => 'Judul buku wajib diisi.',
            'author.required'      => 'Nama penulis wajib diisi.',
            'condition.required'   => 'Kondisi buku wajib dipilih.',
            'condition.in'         => 'Kondisi buku tidak valid.',
            'price.required'       => 'Harga wajib diisi.',
            'price.numeric'        => 'Harga harus berupa angka.',
            'price.min'            => 'Harga tidak boleh negatif.',
            'photo.image'          => 'File harus berupa gambar.',
            'photo.max'            => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
