<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $suppliers = [
            [
                'name' => 'PT. ABC',
                'contact_person' => 'Budi',
                'phone' => '08123456789',
                'address' => 'Jl. Raya No. 1',
            ],
            [
                'name' => 'PT. DEF',
                'contact_person' => 'Agus',
                'phone' => '08123456788',
                'address' => 'Jl. Raya No. 2',
            ],
            [
                'name' => 'PT. GHI',
                'contact_person' => 'Joko',
                'phone' => '08123456787',
                'address' => 'Jl. Raya No. 3',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
