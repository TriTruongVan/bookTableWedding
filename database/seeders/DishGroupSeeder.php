<?php

namespace Database\Seeders;

use App\Models\DishGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DishGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Start seeding a DishGroup");
        $dishGroup = [
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
        foreach ($dishGroup as $item){
            DishGroup::firstOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
        $this->command->info("Finish seeding a DishGroup");
    }
}
