<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpenNewAcctRequest extends FormRequest
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
            'email' => ['required', 'string', 'max:255', 'lowercase'],
            'type' => ['required', 'string', 'lowercase', 'max:11'],
            'currency' => ['required', 'string', 'uppercase', 'max:3'],
            'amount' => ['required', 'numeric', 'decimal:2']
        ];
    }
}
