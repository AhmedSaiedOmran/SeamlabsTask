<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $items = [
            ['name' => 'Chicken Ranch Pizza', 'price' => 290 ],
            ['name' => 'Pepperoni Pizza', 'price' => 275 ],
            ['name' => '6 Cheese Pizza', 'price' => 295 ],
            ['name' => 'Chocolate Pie', 'price' => 75 ],
            ['name' => 'Pepsi', 'price' => 20 ],
            ['name' => 'Small Mineral Water', 'price' => 12 ],
        ];

        foreach ($items as $item) {
            MenuItem::create($item);
        }
    }
}
