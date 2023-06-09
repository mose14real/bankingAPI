<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditDebitRequest extends FormRequest
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
            'acctNumber' => ['required', 'string', 'min:10', 'max:10'],
            'byName' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'decimal:2', 'min:0.01']

        ];
    }
}
