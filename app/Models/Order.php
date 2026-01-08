<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\HasUppercaseUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "orders";

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'customer_id',
        'event_date',
        'lunar_day',
        'lunar_month',
        'lunar_year',
        'session',
        'table_count',
        'price_per_table',
        'total_amount',
        'deposit_amount',
        'note',
        'status'
    ];

    protected $casts = [
        'event_date' => 'date',
        'status'     => OrderStatus::class,
        'deposit_amount'  => 'decimal:2',
        'price_per_table' => 'decimal:2',
        'total_amount'    => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        //TODO handle concurrent creating
        static::creating(function ($order) {
            if (!$order->id) {
                $datePart = now()->format('ymd');

                $lastNumber = self::where('id', 'like', $datePart . '-%')
                    ->selectRaw("MAX(CAST(SUBSTR(id, INSTR(id, '-') + 1) AS UNSIGNED)) as max_number")
                    ->value('max_number');

                $newNumber = $lastNumber ? $lastNumber + 1 : 1;

                $order->id = $datePart . '-' . $newNumber;
            }
        });
    }

    protected $attributes = [
        'status' => OrderStatus::SAN_SANG,
    ];

    protected $appends = [
        'lunar_date'
    ];

    public function getLunarDateAttribute(): ?string
    {
        // Nếu thiếu bất kỳ trường nào thì trả về null hoặc chuỗi rỗng
        if (!$this->lunar_day || !$this->lunar_month || !$this->lunar_year) {
            return null;
        }
        return "Mồng {$this->lunar_day} Tháng {$this->lunar_month} Năm {$this->lunar_year}";
    }

    public function getLunarDateShortAttribute(): ?string
    {
        if (!$this->lunar_day || !$this->lunar_month || !$this->lunar_year) {
            return null;
        }
        return "{$this->lunar_day}/{$this->lunar_month}/{$this->lunar_year}";
    }

    public function getEventDateFormattedAttribute(): ?string
    {
        return $this->event_date 
            ? Carbon::parse($this->event_date)->format('d/m/Y')
            : null;
    }

    public function staffs()
    {
        return $this->belongsToMany(Staff::class, 'order_staff')
                ->withPivot(['event_date', 'session'])
                ->withTimestamps();
    }

    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'order_dishes');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'voucher_orders');
    }
}
