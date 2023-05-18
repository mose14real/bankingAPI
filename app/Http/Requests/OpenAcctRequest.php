<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class OpenAcctRequest extends FormRequest
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
            'email' => ['required', 'string', 'unique:users', 'max:255', 'lowercase'], //'email:rfc,dns'
            'phone' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
            // 'role' => ['required', 'string', new Enum(ServerStatus::class), 'lowercase'],
            'bvn' => ['required', 'string', 'min:11', 'max:11'],
            'employment' => ['required', 'string', 'max:14'],
            'marital' => ['required', 'string', 'max:8'],
            'maiden' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'nationality' => ['required', 'string', 'max:255'],
            // 'acct_no' => ['required', 'unique:accounts', 'numeric', 'min:10', 'max:10'],
            'acct_type' => ['required', 'string', 'lowercase', 'max:11'],
            // 'acct_status' => ['required', 'string', new Enum(ServerStatus::class)],
            'currency' => ['required', 'string', 'uppercase', 'max:3'],
            'balance' => ['required', 'numeric', 'decimal:2'],
            // 'pin' => ['required', 'numeric',],
            // 'officer_name' => ['required', 'string'],
            // 'officer_email' => ['required', 'string', 'lowercase'],
            // 'officer_phone' => ['required'],
            // 'transact_date' => ['required'],
            'transact_desc' => ['required', 'string', 'max:255'],
            // 'transact_type' => ['required', 'string', new Enum(ServerStatus::class), 'lowercase'],
            // 'transact_amount' => ['required', 'numeric', 'decimal:2'],
            // 'transact_reference' => ['required', 'string'],
            // 'transact_status' => ['required', 'string', new Enum(ServerStatus::class), 'lowercase'],
        ];
    }
}
