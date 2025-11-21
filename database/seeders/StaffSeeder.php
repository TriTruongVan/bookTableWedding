<?php

namespace Database\Seeders;

use App\Enums\StaffStatus;
use App\Models\Staff;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Start seeding a staff");

        $staff = [
            [
                [
                    'tel' => '0789494098',
                ],
                [
                    'name' => 'Anh Tèo',
                    'street' => '50 Lưu Quý Kỳ',
                    'ward' => 24,
                    'city' => 1,
                    'status' => StaffStatus::SERVE,
                ],
            ],
            [
                [
                    'tel' => '0902651571',
                ],
                [
                    'name' => 'Chị Gái',
                    'street' => '50 Lưu Quý Kỳ',
                    'ward' => 20,
                    'city' => 1,
                    'status' => StaffStatus::BEP,
                ],
            ],
            [
                [
                    'tel' => '0799435577',
                ],
                [
                    'name' => 'Anh Can',
                    'street' => '17 Nguyễn Phúc Tần',
                    'ward' => 24,
                    'city' => 1,
                    'status' => StaffStatus::SERVE,
                ],
            ],
        ];

        foreach ($staff as $staff) {
            Staff::updateOrCreate(...$staff);
        }

        $this->command->info("Finish seeding a staff");
    }
}
