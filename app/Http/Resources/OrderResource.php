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
                'street' => $this->customer?->street,
                'ward' => $this->customer?->ward,
            ],
            'schedule' => [
                'lunar_day'       => $this->lunar_day,
                'lunar_month'     => $this->lunar_month,
                'lunar_year'      => $this->lunar_year,
                'isLeapMonth'=> (bool) $this->isLeapMonth,
                'session' => $this->session,
                'lunarCanChi'=> $this->lunar_can_chi,
            ],
            'event_date' => $this->event_date_formatted,
            'lunar_date' => $this->lunar_date,
            'address' => $this->customer?->address,
            'dishes' => $this->dishes->map(fn ($dish) => [
                'id' => $dish->id,
                'name' => $dish->name,
                'dish_group_name' => $dish->dishGroup?->name
            ]),
            'dish_count' => $this->dishes->count(),
            'table_count' => $this->table_count,
            'price_per_table' => $this->price_per_table,
            'total_amount'    => $this->total_amount,
            'deposit_amount'  => $this->deposit_amount,
            'staffs' => $this->staffs->map(fn ($staff) => [
                'id' => $staff->id,
                'name' => $staff->name,
                'tel' => $staff->tel,
            ]),
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'note' => $this->note,
            'vouchers' => $this->vouchers->map(fn ($voucher) => [
                'id' => $voucher->id,
                'name' => $voucher->name,
                'conditions' => $voucher->conditions,
            ]),
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
        ];
    }
}
