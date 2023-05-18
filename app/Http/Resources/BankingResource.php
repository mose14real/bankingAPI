<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BanksResource extends JsonResource
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

            'id' => (string)$this->id,

            'customers' => [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'bvn' => $this->bvn,
                'employment' => $this->employment,
                'marital' => $this->marital,
                'maide' => $this->maiden,
                'address' => $this->address,
                'nationality' => $this->nationality,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ],

            'accounts' => [
                'number' => $this->number,
                'type' => $this->type,
                'status' => $this->status,
                'currency' => $this->currency,
                'balance' => $this->balance,
                'pin' => $this->pin,
                'officer_name' => $this->officer_name,
                'officer_email' => $this->officer_email,
                'officer_phone' => $this->officer_phone,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ],

            'transactions' => [
                'date' => $this->date,
                'description' => $this->description,
                'type' => $this->type,
                'amount' => $this->amount,
                'reference' => $this->reference,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ],

            'relationships' => [
                'id' => (string)$this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                '`phone' => $this->user->phone,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ]
        ];
    }
}
