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
            ['name' => 'PT. Sumber Suku Cadang', 'contact_info' => 'Jl. Industri No.123, Jakarta'],
            ['name' => 'CV. Pemasok Ban', 'contact_info' => 'Jl. Ban Karet No.456, Bandung'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
