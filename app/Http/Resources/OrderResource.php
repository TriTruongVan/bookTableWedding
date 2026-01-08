<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer' => [
                'id' => $this->customer?->id,
                'name' => $this->customer?->name,
                'tel' => $this->customer?->tel,
            ],
            'event_date' => $this->event_date_formatted,
            'lunar_date' => $this->lunar_date,
            'address' => $this->customer?->address,
            'dishes' => $this->dishes?->name,
            'dish_count' => $this->dishes->count(),
            'table_count' => $this->table_count,
            'price_per_table' => $this->price_per_table,
            'total_amount'    => $this->total_amount,
            'deposit_amount'  => $this->deposit_amount,
            'staffs' => $this->staffs?->name,
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
        ];
    }
}
