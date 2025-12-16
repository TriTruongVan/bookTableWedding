<?php

namespace Database\Seeders;

use App\Models\ServiceGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Start seeding a GroupService");
        $groupServices = [
            [
                'name' => 'KHAI VỊ',
            ],
            [
                'name' => 'SÚP',
            ],
            [
                'name' => 'GÀ',
            ],
            [
                'name' => 'VỊT',
            ],
            [
                'name' => 'BÒ',
            ],
            [
                'name' => 'DÊ',
            ],
            [
                'name' => 'MỰC',
            ],
            [
                'name' => 'TÔM',
            ],
            [
                'name' => 'MÓN CHÍNH',
            ],
            [
                'name' => 'MÓN ĂN THEO YÊU CẦU',
            ],
            [
                'name' => 'TRÁNG MIỆNG',
            ],
        ];
        foreach ($groupServices as $group){
            ServiceGroup::updateOrCreate(
                ['name' => $group['name']],
                ['name' => $group['name']]
            );
        }
        $this->command->info("Finish seeding a GroupService");
    }
}
