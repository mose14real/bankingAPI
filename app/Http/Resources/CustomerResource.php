<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'Customer Info' => [
                'ID' => $this->id,
                'UUID' => $this->uuid,
                'User ID' => $this->user_id,
                'BVN' => $this->bvn,
                'Employment' => $this->employment,
                'Marital Status' => $this->marital,
                'Maiden Name' => $this->maiden,
                'Address' => $this->address,
                'Nationality' => $this->nationality,
                'Created At' => $this->created_at,
                'Updated At' => $this->updated_at
            ],

            'Relationships' => [
                'Account Info' => $this->customer->accounts,
                'Transaction Info' => $this->customer->accounts->transactions,
                'User Info' => $this->user
            ]
        ];
    }
}
