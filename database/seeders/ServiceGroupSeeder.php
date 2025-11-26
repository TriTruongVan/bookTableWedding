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
                'name' => 'khai Vị',
            ],
            [
                'name' => 'Súp',
            ],
            [
                'name' => 'Gà',
            ],
            [
                'name' => 'Vịt',
            ],
            [
                'name' => 'Bò',
            ],
            [
                'name' => 'Dê',
            ],
            [
                'name' => 'Mực',
            ],
            [
                'name' => 'Tôm',
            ],
            [
                'name' => 'Món Chính',
            ],
            [
                'name' => 'Món ăn theo yêu cầu',
            ],
            [
                'name' => 'Tráng Miệng',
            ],
        ];
        foreach ($groupServices as $group) {
            ServiceGroup::updateOrCreate(
                ['name' => $group['name']],
                ['name' => $group['name']]
            );
        }
        $this->command->info("Finish seeding a GroupService");
    }
}
