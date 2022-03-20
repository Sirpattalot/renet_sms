<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //if making a request for billing info, include billing fields.
        if ($request->query('billing')) {
            return [
                'from' => $this->from,
                'to' => $this->to,
                'send_price' => $this->send_price,
                'receive_price' => $this->receive_price,
                'created_at'=> $this->created_at,
            ];           
        }

        //remove id, updated_at, and pricing from API response
        return [
            'from' => $this->from,
            'to' => $this->to,
            'status' => $this->status,
            'body' => $this->body,
            'created_at'=> $this->created_at,
        ];

    }
}
