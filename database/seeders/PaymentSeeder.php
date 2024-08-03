<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $invoice1 = Invoice::where('total_amount', 150000)->first();

        Payment::create(['invoice_id' => $invoice1->id, 'amount' => 150000, 'payment_date' => '2023-08-02']);
    }
}
