<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use phpDocumentor\Reflection\Types\Null_;
use phpDocumentor\Reflection\Types\Nullable;

class InvoiceItemsTableSeeder extends Seeder
{
    public function run()
    {
        // Fetching invoices based on the total amount
        $invoice1 = Invoice::where('total_amount', 180000)->first();
        $invoice2 = Invoice::where('total_amount', 550000)->first();

        // Fetching items based on the updated product names
        $item1 = Item::where('name', 'Oli Castrol GTX Ultraclean 10W-40')->first();
        $item2 = Item::where('name', 'Ban Bridgestone Ecopia EP150 185/70 R14')->first();

        // Inserting invoice items
        InvoiceItem::create([
            'invoice_id' => $invoice1->id,
            'item_id' => $item1->id,
            'name' => $item1->name,
            'quantity' => 2,
            'price' => 75000,
            'total' => 150000,
            'type' => 'product',
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice1->id,
            'item_id' => Null,
            'name' => 'Jasa Ganti Oli',
            'quantity' => 1,
            'price' => 50000,
            'total' => 50000,
            'type' => \App\Models\InvoiceItem::TYPE_SERVICE,
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice2->id,
            'item_id' => $item2->id,
            'name' => $item1->name,
            'quantity' => 1,
            'price' => 500000,
            'total' => 500000,
            'type' => 'product',
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice2->id,
            'item_id' => Null,
            'name' => 'Jasa Ganti Ban',
            'quantity' => 1,
            'price' => 30000,
            'total' => 30000,
            'type' => \App\Models\InvoiceItem::TYPE_SERVICE,
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice2->id,
            'item_id' => Null,
            'name' => 'Barang Lainnya',
            'quantity' => 1,
            'price' => 30000,
            'total' => 30000,
            'type' => 'custom',
        ]);
    }
}
