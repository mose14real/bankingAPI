<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'sender_acctNumber' => ['required', 'string', 'min:10', 'max:10'],
            'receiver_acctNumber' => ['required', 'string', 'min:10', 'max:10'],
            'amount' => ['required', 'numeric', 'decimal:2', 'min:0.01']
        ];
    }
}
