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
            [
                'name' => 'Oli Castrol GTX Ultraclean 10W-40',
                'description' => 'Oli mesin semi-sintetis yang membantu menjaga mesin tetap bersih, mengurangi endapan kotoran, dan memberikan perlindungan optimal untuk mesin mobil Anda.',
                'quantity' => 50,
                'price' => 180000,
                'min_stock' => 10
            ],
            [
                'name' => 'Ban Bridgestone Ecopia EP150 185/70 R14',
                'description' => 'Ban radial hemat bahan bakar yang menawarkan cengkeraman yang baik di jalan basah maupun kering, serta memberikan kenyamanan berkendara.',
                'quantity' => 20,
                'price' => 550000,
                'min_stock' => 5
            ],
            [
                'name' => 'Busi NGK Iridium IX',
                'description' => 'Busi NGK Iridium IX memberikan percikan api yang kuat untuk pembakaran yang lebih efisien, meningkatkan performa mesin dan keawetan komponen.',
                'quantity' => 100,
                'price' => 95000,
                'min_stock' => 20
            ],
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
