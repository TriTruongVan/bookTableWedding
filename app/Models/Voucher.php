<?php

namespace App\Models;

use App\Enums\VoucherStatus;
use App\HasUppercaseUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes, HasUppercaseUlids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = "vouchers";
    protected $fillable = [
        'code',
        'name',
        'description',
        'conditions',
        'is_active'
    ];
    
    protected $casts = [
        'conditions' => 'array',
        'is_active' => VoucherStatus::class,
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'voucher_orders');
    }
}
