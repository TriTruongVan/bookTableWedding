<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dish extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'dish_group_id',
        'description',
    ];

    public function dishGroup()
    {
        return $this->belongsTo(DishGroup::class);
    }

    public function orders() 
    {
        return $this->belongsToMany(Dish::class, 'order_dishes');
    }
}
