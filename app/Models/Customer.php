<?php

namespace App\Models;

use App\Enums\City;
use App\Enums\CustomerRole;
use App\Enums\Ward;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "customers";
    protected $fillable = [
        'name',
        'role',
        'street',
        'ward',
        'city',
        'tel'
    ];

    protected $appends = [
        'address',
    ];

    protected $casts = [
        'role' => CustomerRole::class,
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
}
