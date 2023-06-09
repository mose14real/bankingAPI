<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
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
            'Account Info' => [
                'ID' => $this->id,
                'Customer ID' => $this->customer_id,
                'Number' => $this->acct_number,
                'Type' => $this->type,
                'Status' => $this->status,
                'Currency' => $this->currency,
                'Balance' => $this->available_balance,
                'PIN' => $this->pin,
                'Officer Name' => $this->officer_name,
                'Officer Email' => $this->officer_email,
                'Officer Phone' => $this->officer_phone,
                'Created At' => $this->created_at,
                'Updated At' => $this->updated_at
            ],

            'Relationships' => [
                'User Info' => $this->customer->user,
                'Transaction Info' => $this->transactions,
                'Customer Info' => $this->customer
            ]
        ];
    }
}
