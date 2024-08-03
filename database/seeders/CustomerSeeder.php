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
        Customer::create(['name' => 'Ahmad Riyadi', 'email' => 'ahmad@gmail.com', 'phone' => '081223344556', 'address' => 'Jl. Kemerdekaan No. 45, Yogyakarta']);
        Customer::create(['name' => 'Siti Aminah', 'email' => 'siti@gmail.com', 'phone' => '081223344557', 'address' => 'Jl. Diponegoro No. 12, Medan']);
        Customer::create(['name' => 'Bambang Sutrisno', 'email' => 'bambang@gmail.com', 'phone' => '081223344558', 'address' => 'Jl. Panglima No. 88, Makassar']);
    }
}
