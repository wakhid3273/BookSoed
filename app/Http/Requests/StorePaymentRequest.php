<?php

namespace App\Http\Requests;

use App\Models\Payment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $order = $this->route('order');
        return $order && $this->user()->can('create', [Payment::class, $order]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'string', Rule::in([Payment::METHOD_E_WALLET, Payment::METHOD_COD])],
            'ewallet_provider' => [
                Rule::requiredIf(fn () => $this->input('payment_method') === Payment::METHOD_E_WALLET),
                'nullable',
                'string',
                Rule::in(Payment::PROVIDERS),
            ],
        ];
    }

    /**
     * Custom messages for validation.
     */
    public function messages(): array
    {
        return [
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'ewallet_provider.required_if' => 'Penyedia E-Wallet wajib dipilih untuk metode E-Wallet.',
            'ewallet_provider.in' => 'Penyedia E-Wallet tidak valid.',
        ];
    }
}
