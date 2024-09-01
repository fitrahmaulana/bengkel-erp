<?php
namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoicesTableSeeder extends Seeder
{
    public function run()
    {
        $customer1 = Customer::where('name', 'Ahmad Riyadi')->first();
        $customer2 = Customer::where('name', 'Siti Aminah')->first();

        Invoice::create([
            'customer_id' => $customer1->id,
            'invoice_date' => '2023-08-01',
            'total_amount' => 180000,
            'status' => 'paid',
        ]);

        Invoice::create([
            'customer_id' => $customer2->id,
            'invoice_date' => '2023-08-03',
            'total_amount' => 550000,
            'status' => 'unpaid',
        ]);
    }
}
