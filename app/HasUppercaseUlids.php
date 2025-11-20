<?php

namespace App;

use Illuminate\Support\Str;

trait HasUppercaseUlids
{
    protected static function bootHasUppercaseUlids()
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = Str::upper(Str::ulid());
            }
        });
    }
}
