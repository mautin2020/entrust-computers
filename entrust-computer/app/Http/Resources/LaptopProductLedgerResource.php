<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LaptopProductLedgerResource extends JsonResource
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
            'date' => $this->date,
            'product_code' => $this->product_code,
            'opening_stock' => $this->opening_stock,
            'closing_stock' => $this->closing_stock,
            'description' => $this->description,
            'particular' => $this->particular,
            'ref_no' => $this->ref_no,
            'sales' => $this->sales,
            'supply' => $this->supplly,
            'balance' => $this->balance,
            'created_at_dates' => [
                'created_at_human' => $this->created_at->diffForHumans(),
            ],
        ];
    }
}