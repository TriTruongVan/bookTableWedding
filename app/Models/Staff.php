<?php

namespace App\Models;

use App\Enums\City;
use App\Enums\Ward;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "staff";
    protected $fillable = [
        'name',
        'tel',
        'street',
        'ward',
        'city',
        'status',
    ];

    protected $appends = [
        'address',
    ];

    public function getAddressAttribute() {
        $wardLabel = Ward::from($this->ward)->getLabel();
        $cityLabel = City::from($this->city)->getLabel();
        return implode(', ', array_filter([$this->street, $wardLabel, $cityLabel]));
    }
    
    public function getDistrictAttribute() {
        $wardLabel = Ward::from($this->ward)->getLabel();
        $cityLabel = City::from($this->city)->getLabel();
        return implode(', ', array_filter([$wardLabel, $cityLabel]));
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_staff')
                    ->withPivot(['event_date', 'session'])
                    ->withTimestamps();
    }
}
