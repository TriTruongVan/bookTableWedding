<?php

namespace App\Models;

use App\Enums\City;
use App\Enums\Ward;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    use HasUlids;
    protected $fillable = [
        'order_id',
        'street',
        'ward',
        'city',
    ];

    protected $appends = [
        'full_address',
    ];

    public function getFullAddressAttribute() {
        $wardLabel = Ward::from($this->ward)->getLabel();
        $cityLabel = City::from($this->city)->getLabel();
        return implode(', ', array_filter([$this->street, $wardLabel, $cityLabel]));
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
