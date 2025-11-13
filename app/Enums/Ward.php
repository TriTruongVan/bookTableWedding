<?php

namespace App\Enums;

enum Ward: int
{
    case HAI_CHAU = 1;
    case HOA_CUONG = 2;
    case THANH_KHE = 3;
    case AN_KHE = 4;
    case AN_HAI = 5;
    case SON_TRA = 6;
    case NGU_HANH_SON = 7;
    case HOA_KHANH = 8;
    case HAI_VAN = 9;
    case LIEN_CHIEU = 10;
    case CAM_LE = 11;
    case HOA_XUAN = 12;
    case HOA_VANG = 13;
    case HOA_TIEN = 14;
    case BA_NA = 15;
    case TAM_KY = 16;
    case QUANG_PHU = 17;
    case HUONG_TRA = 18;
    case BAN_THACH = 19;
    case DIEN_BAN = 20;
    case DIEN_BAN_DONG = 21;
    case AN_THANG = 22;
    case DIEN_BAN_BAC = 23;
    case HOI_AN = 24;
    case HOI_AN_DONG = 25;
    case HOI_AN_TAY = 26;

    public function getLabel(): string
    {
        return match ($this) {
            Ward::HAI_CHAU => 'Hải Châu',
            Ward::HOA_CUONG => 'Hòa Cường',
            Ward::THANH_KHE => 'Thanh Khê',
            Ward::AN_KHE => 'An Khê',
            Ward::AN_HAI => 'An Hải',
            Ward::SON_TRA => 'Sơn Trà',
            Ward::NGU_HANH_SON => 'Ngũ Hành Sơn',
            Ward::HOA_KHANH => 'Hòa Khánh',
            Ward::HAI_VAN => 'Hải Vân',
            Ward::LIEN_CHIEU => 'Liên Chiểu',
            Ward::CAM_LE => 'Cẩm Lệ',
            Ward::HOA_XUAN => 'Hòa Xuân',
            Ward::HOA_VANG => 'Hòa Vang',
            Ward::HOA_TIEN => 'Hòa Tiến',
            Ward::BA_NA => 'Bà Nà',
            Ward::TAM_KY => 'Tam Kỳ',
            Ward::QUANG_PHU => 'Quảng Phú',
            Ward::HUONG_TRA => 'Hương Trà',
            Ward::BAN_THACH => 'Bàn Thạch',
            Ward::DIEN_BAN => 'Điện Bàn',
            Ward::DIEN_BAN_DONG => 'Điện Bàn Đông',
            Ward::AN_THANG => 'An Thắng',
            Ward::DIEN_BAN_BAC => 'Điện Bàn Bắc',
            Ward::HOI_AN => 'Hội An',
            Ward::HOI_AN_DONG => 'Hội An Đông',
            Ward::HOI_AN_TAY => 'Hội An Tây',
        };
    }
}
