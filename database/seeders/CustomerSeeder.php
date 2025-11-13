<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Start seeding a customer");
        
        $customers = [
            [
                [
                    'tel' => '0764161834',
                ],
                [
                    'name' => 'Trần Thị Thảo Vy',
                    'street' => '17 Nguyễn Phúc Tần',
                    'ward' => 24,
                    'city' => 1
                ]
            ],
            [
                [
                    'tel' => '0336880153',
                ],
                [
                    'name' => 'Trương Văn Trí',
                    'street' => '304 Hùng Vương',
                    'ward' => 26,
                    'city' => 1
                ]
            ],
        ];
        foreach ($customers as $customer) {
            Customer::updateOrCreate(...$customer);
        }

        $this->command->info("Finish seeding a customer");
    }
}
