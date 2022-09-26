<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchasesResource extends JsonResource
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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'sms_id' => $this->sms_id,
            'body' => $this->body,
            'amount' => $this->amount,
            'place' => $this->place,
            'balance' => $this->balance,
            'buy_at' => $this->buy_at,
        ];
    }
}
