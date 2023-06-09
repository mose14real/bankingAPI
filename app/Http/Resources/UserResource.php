<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'User Info' => [
                'ID' => $this->id,
                'Full Name' => $this->name,
                'Email' => $this->email,
                'Phone No' => $this->phone_number,
                'Password' => $this->password,
                'Role' => $this->role,
                'Remember Token' => $this->remember_token,
                'Created At' => $this->created_at,
                'Updated At' => $this->updated_at
            ],

            'Relationships' => [
                'Customer Info' => $this->customer,
                'Account Info' => $this->customer->accounts,
                'Transaction Info' => $this->customer->accounts->transactions
            ]
        ];
    }
}
