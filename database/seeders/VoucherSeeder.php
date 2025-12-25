<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Start seeding vouchers");
        
        $vouchers = [
            [
                'code' => 'BACKDROP',
                'name' => 'Backdrop chụp hình',
                'description' => 'Voucher tặng backdrop chụp hình cho tiệc cưới, sinh nhật, sự kiện.',
                'conditions' => [
                    'min_tables' => 50
                ],
                'is_active' => 1,
            ],
            [
                'code' => 'TABLE_GIFT',
                'name' => 'Tặng 1 bàn tiệc cho gia đình',
                'description' => 'Voucher tặng 1 bàn tiệc miễn phí cho gia đình.',
                'conditions' => [
                    'min_tables' => 50
                ],
                'is_active' => 1,
            ],
            [
                'code' => 'FREE_MC',
                'name' => 'Free MC',
                'description' => 'Voucher miễn phí MC dẫn chương trình.',
                'conditions' => [
                    'min_tables' => 50
                ],
                'is_active' => 1,
            ],
            [
                'code' => 'HAT_DUA',
                'name' => 'Hạt dưa',
                'description' => 'Voucher miễn phí hạt dưa.',
                'conditions' => [
                    'min_tables' => 50
                ],
                'is_active' => 1,
            ],
            [
                'code' => 'WEDDING_CAR',
                'name' => 'Xe đưa đón dâu',
                'description' => 'Voucher tặng xe đưa đón dâu trong ngày cưới.',
                'conditions' => [
                    'min_tables' => 50
                ],
                'is_active' => 1,
            ],
            [
                'code' => 'DUONG_DAN_LOI',
                'name' => 'Đường dẫn lỗi',
                'description' => 'Voucher tặng đường dẫn lối trong ngày cưới.',
                'conditions' => [
                    'min_tables' => 50
                ],
                'is_active' => 1,
            ],
            [
                'code' => 'THAM_DAN_LOI',
                'name' => 'Thảm dẫn lỗi',
                'description' => 'Voucher tặng thảm dẫn lối trong ngày cưới.',
                'conditions' => [
                    'min_tables' => 50
                ],
                'is_active' => 1,
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::updateOrCreate(
                ['code' => $voucher['code']],
                array_merge($voucher, ['id' => (string) Str::ulid()])
            );
        }

        $this->command->info("Finish seeding vouchers");
    }
}
