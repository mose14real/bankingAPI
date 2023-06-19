<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class CreateAllRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'unique:users', 'max:255', 'lowercase'],
            'phone' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'bvn' => ['required', 'string', 'min:11', 'max:11'],
            'employment' => ['required', 'string', 'max:14'],
            'marital' => ['required', 'string', 'max:8'],
            'maiden' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'nationality' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'lowercase', 'max:11'],
            'currency' => ['required', 'string', 'uppercase', 'max:3'],
            'amount' => ['required', 'numeric', 'decimal:2', 'min:0.00']
        ];
    }
}
