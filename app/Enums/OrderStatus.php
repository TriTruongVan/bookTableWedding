<?php

namespace App\Enums;

enum OrderStatus : string
{
    case SAN_SANG = 'san_sang';
    case KET_THUC = 'ket_thuc';

    public function label(): string
    {
        return match ($this) {
            self::SAN_SANG     => 'Sẵn sàng',
            self::KET_THUC => 'Kết thúc',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SAN_SANG     => 'warning',
            self::KET_THUC => 'success',
        };
    }
}
