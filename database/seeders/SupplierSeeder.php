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
                'name' => 'PT. Pertamina Lubricants',
                'contact_person' => 'Ahmad',
                'phone' => '02112345678',
                'address' => 'Jl. Medan Merdeka Timur No.1A, Jakarta',
            ],
            [
                'name' => 'Bridgestone Tire Indonesia',
                'contact_person' => 'Suryo',
                'phone' => '02187654321',
                'address' => 'Jl. Raya Bekasi No.1, Karawang',
            ],
            [
                'name' => 'PT. NGK Busi Indonesia',
                'contact_person' => 'Faisal',
                'phone' => '02111223344',
                'address' => 'Jl. Raya Bogor KM 26, Jakarta Timur',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
