<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'book_date' => ['required', 'date', 'after_or_equal:today'],
            'time_slot' => ['required', 'string'],
            'payment_type' => ['required', 'in:DP,FULL,dp,full'],
            'payment_method' => ['required', 'in:QRIS,BCA,Mandiri,BRI,DANA,GoPay'],
        ];
    }

    /**
     * Custom Indonesian validation messages (PRD 5.B).
     */
    public function messages(): array
    {
        return [
            'book_date.required' => 'Tanggal pertandingan wajib dipilih.',
            'book_date.date' => 'Format tanggal pertandingan tidak valid.',
            'book_date.after_or_equal' => 'Tanggal pertandingan tidak boleh di masa lalu.',
            'time_slot.required' => 'Sesi waktu bermain wajib dipilih.',
            'payment_type.required' => 'Tipe pembayaran (DP atau Lunas) wajib dipilih.',
            'payment_type.in' => 'Tipe pembayaran harus DP (50%) atau Lunas (100%).',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran yang dipilih tidak valid.',
        ];
    }
}
