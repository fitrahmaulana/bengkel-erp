<?php

namespace App\Livewire\Forms;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use Livewire\Attributes\Validate;
use Livewire\Form;

class InvoiceForm extends Form
{

    public $customer_id, $invoice_date, $total_amount = 0;
    public $invoiceItems = [], $customItems = [];

    // rule
    protected function rules()
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'invoiceItems.*.item_id' => 'required|exists:items,id',
            'invoiceItems.*.quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $item = $this->invoiceItems[$index];
                    $stock = Item::find($item['item_id'])->stock;

                    if ($value > $stock) {
                        $fail("Kuantitas melebihi stok yang tersedia ({$stock}).");
                    }
                },
            ],
        ];
    }

    protected function messages()
    {
        return [
            'customer_id.required' => 'Pelanggan tidak boleh kosong.',
            'customer_id.exists' => 'Pelanggan tidak ditemukan.',
            'invoice_date.required' => 'Tanggal faktur tidak boleh kosong.',
            'invoice_date.date' => 'Tanggal faktur harus berupa tanggal.',
            'invoiceItems.*.item_id.required' => 'Item tidak boleh kosong.',
            'invoiceItems.*.item_id.exists' => 'Item tidak ditemukan.',
            'invoiceItems.*.quantity.required' => 'Kuantitas tidak boleh kosong.',
            'invoiceItems.*.quantity.integer' => 'Kuantitas harus berupa angka.',
            'invoiceItems.*.quantity.min' => 'Kuantitas minimal 1.',
        ];
    }

    public function store()
    {
        $invoice = Invoice::create([
            'customer_id' => $this->customer_id,
            'invoice_date' => $this->invoice_date,
            'total_amount' => $this->total_amount,
            'status' => 'unpaid',
        ]);

        // Membuat array invoice items dari input
        $invoiceItemsData = array_map(function ($item) {
            return new InvoiceItem([
                'item_id' => $item['item_id'],
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'type' => 'product',
                'total' => $item['total'],
            ]);
        }, $this->invoiceItems);

        // Menyimpan invoice items menggunakan relasi
        $invoice->invoiceItems()->saveMany($invoiceItemsData);

        // Mengurangi stok
        foreach ($this->invoiceItems as $item) {
            $itemModel = Item::find($item['item_id']);
            if ($itemModel) {
                $itemModel->decrement('stock', $item['quantity']);
            }
        }

        // Untuk custom items, prosesnya mirip
        $customItemsData = array_map(function ($customItem) {
            return new InvoiceItem([
                'item_id' => null,
                'name' => $customItem['name'],
                'quantity' => $customItem['quantity'],
                'price' => $customItem['price'],
                'total' => $customItem['total'],
                'type' => $customItem['type'],
            ]);
        }, $this->customItems);

        $invoice->invoiceItems()->saveMany($customItemsData);

        $this->reset(['customer_id', 'invoice_date', 'invoiceItems', 'customItems', 'total_amount']);
    }
}
