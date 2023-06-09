<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'Transaction Info' => [
                'ID' => $this->id,
                'Account ID' => $this->account_id,
                'Date&Time' => $this->date_time,
                'Sender Name' => $this->sender_name,
                'Sender Acct' => $this->sender_acct,
                'Receiver Name' => $this->receiver_name,
                'Sender Acct' => $this->receiver_acct,
                'Description' => $this->description,
                'Type' => $this->type,
                'Amount' => $this->amount,
                'Opening Balance' => $this->opening_balance,
                'Closing Balance' => $this->closing_balance,
                'Reference' => $this->reference,
                'Status' => $this->transact_status,
                'Created At' => $this->created_at,
                'Updated At' => $this->updated_at
            ],

            'Relationships' => [
                'Account Info' => $this->accounts,
                'Customer Info' => $this->accounts->customer,
                'User Info' => $this->accounts->customer->user
            ]
        ];
    }
}
