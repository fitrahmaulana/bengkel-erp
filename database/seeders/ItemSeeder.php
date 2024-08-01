<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\StockMovement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Oli Mesin', 'description' => 'Oli mesin sintetis 5W-30', 'quantity' => 50, 'price' => 150000, 'min_stock' => 10],
            ['name' => 'Ban Mobil', 'description' => 'Ban radial 185/70 R14', 'quantity' => 20, 'price' => 500000, 'min_stock' => 5],
            ['name' => 'Busi', 'description' => 'Busi iridium', 'quantity' => 100, 'price' => 75000, 'min_stock' => 20],
        ];

        foreach ($items as $item) {
            $itemModel = Item::create($item);
            StockMovement::create([
                'item_id' => $itemModel->id,
                'type' => 'in',
                'quantity' => $itemModel->quantity,
            ]);
        }
    }
}
