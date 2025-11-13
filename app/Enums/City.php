<?php

namespace App\Enums;

enum City: int
{
    case DA_NANG = 1;

    public function getLabel()
    {
        return match ($this)  {
            City::DA_NANG => 'Đà Nẵng',
        };
    }
}

