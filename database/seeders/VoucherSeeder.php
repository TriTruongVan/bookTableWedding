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
                    'min_tables' => 60
                ],
                'is_active' => 1,
            ],
            [
                'code' => 'TABLE_GIFT',
                'name' => 'Tặng 1 bàn tiệc cho gia đình',
                'description' => 'Voucher tặng 1 bàn tiệc miễn phí cho gia đình khi đặt từ 50 bàn trở lên.',
                'conditions' => [
                    'min_tables' => 50
                ],
                'is_active' => 1,
            ],
            [
                'code' => 'FREE_MC',
                'name' => 'Free MC',
                'description' => 'Voucher miễn phí MC dẫn chương trình khi đặt từ 50 bàn trở lên.',
                'conditions' => [
                    'min_tables' => 60
                ],
                'is_active' => 1,
            ],
            [
                'code' => 'ACOUSTIC',
                'name' => 'Ban nhạc acoustic khai tiệc',
                'description' => 'Voucher miễn phí ban nhạc acoustic khai tiệc khi đặt từ 70 bàn trở lên.',
                'conditions' => [
                    'min_tables' => 70
                ],
                'is_active' => 1,
            ],
            [
                'code' => 'WEDDING_CAR',
                'name' => 'Xe đưa đón dâu',
                'description' => 'Voucher tặng xe đưa đón dâu trong ngày cưới khi đặt từ 60 bàn trở lên.',
                'conditions' => [
                    'min_tables' => 60
                ],
                'is_active' => 1,
            ],
            [
                'code' => 'THAM_CO',
                'name' => 'Thảm trải dẫn lối',
                'description' => 'Voucher tặng thảm dẫn lối trong ngày cưới khi đặt từ 50 bàn trở lên.',
                'conditions' => [
                    'min_tables' => 60
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
