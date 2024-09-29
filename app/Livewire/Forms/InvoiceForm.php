<?php

namespace App\Livewire\Forms;

use App\Models\Invoice;
use App\Models\Item;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Form;

class InvoiceForm extends Form
{
    public ?Invoice $invoice;

    #[Validate('required|exists:customers,id')]
    public $customer_id = '';

    #[Validate('required|date')]
    public $invoice_date = '';

    #[Validate('array')]
    public $invoiceItems = [];

    #[Validate('array')]
    public $customItems = [];

    public $total_amount = 0;

    // Custom item fields
    public $customItemName = '';
    public $customItemQuantity = 1;
    public $customItemPrice = '';
    public $customItemType = \App\Models\InvoiceItem::TYPE_SERVICE;

    public function setInvoice(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->customer_id = $invoice->customer_id;
        $this->invoice_date = $invoice->invoice_date;
        $this->loadInvoiceItems();
    }

    private function loadInvoiceItems()
    {
        $this->invoiceItems = $this->invoice->invoiceItems->where('item_id', '!=', null)->map(function ($item) {
            return [
                'item_id' => $item->item_id,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->quantity * $item->price,
            ];
        })->toArray();

        $this->customItems = $this->invoice->invoiceItems->where('item_id', null)->map(function ($item) {
            return [
                'name' => $item->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->quantity * $item->price,
                'type' => $item->type,
            ];
        })->toArray();

        $this->calculateTotal();
    }

    public function addInvoiceItem(Item $item, $quantity)
    {
        $this->invoiceItems[] = [
            'item_id' => $item->id,
            'name' => $item->name,
            'quantity' => $quantity,
            'price' => $item->price,
            'total' => $item->price * $quantity,
        ];
        $this->calculateTotal();
    }

    public function updateInvoiceItem($index, $quantity)
    {
        $item = &$this->invoiceItems[$index];
        $item['quantity'] = $quantity;
        $item['total'] = $item['price'] * $quantity;
        $this->calculateTotal();
    }

    public function removeInvoiceItem($index)
    {
        unset($this->invoiceItems[$index]);

        $this->invoiceItems = array_values($this->invoiceItems);
        $this->calculateTotal();
    }

    public function addCustomItem()
    {
        $this->validate([
            'customItemName' => 'required|string',
            'customItemQuantity' => 'required|numeric|min:1',
            'customItemPrice' => 'required|numeric|min:0',
            'customItemType' => 'required|in:service,custom',
        ]);

        $this->customItems[] = [
            'name' => $this->customItemName,
            'quantity' => $this->customItemQuantity,
            'price' => $this->customItemPrice,
            'total' => $this->customItemPrice * $this->customItemQuantity,
            'type' => $this->customItemType,
        ];
        $this->calculateTotal();
        $this->reset(['customItemName', 'customItemQuantity', 'customItemPrice', 'customItemType' => 'service']);
    }

    public function updateCustomItem($index, $quantity)
    {
        $item = &$this->customItems[$index];
        $item['quantity'] = $quantity;
        $item['total'] = $item['price'] * $quantity;
        $this->calculateTotal();
    }

    public function removeCustomItem($index)
    {
        unset($this->customItems[$index]);
        $this->customItems = array_values($this->customItems);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total_amount = array_sum(array_column($this->invoiceItems, 'total')) +
            array_sum(array_column($this->customItems, 'total'));
    }

    public function update()
    {
        $this->validate();
        DB::beginTransaction();

        try {
            // Update invoice
            $this->invoice->update([
                'customer_id' => $this->customer_id,
                'invoice_date' => $this->invoice_date,
                'total_amount' => $this->total_amount,
            ]);

            // Remove existing invoice items and return stock
            foreach ($this->invoice->invoiceItems as $existingItem) {
                if($existingItem->item_id == null) {
                    continue;
                }
                $itemModel = Item::findOrFail($existingItem->item_id);
                $itemModel->increment('stock', $existingItem->quantity); // Kembalikan stok
            }
            $this->invoice->invoiceItems()->delete();
            // Add new invoice items
            foreach ($this->invoiceItems as $item) {
                $itemModel = Item::findOrFail($item['item_id']);


                if ($itemModel->stock < $item['quantity']) {
                    throw new Exception("Insufficient stock for item: " . $itemModel->name);
                }

                $itemModel->decrement('stock', $item['quantity']); // Kurangi stok
                $this->invoice->invoiceItems()->create($item);
            }

            // Add custom items (no stock management for custom items)
            foreach ($this->customItems as $item) {
                $this->invoice->invoiceItems()->create($item);
            }

            DB::commit();
            return $this->invoice;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function store()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $invoice = Invoice::create([
                'customer_id' => $this->customer_id,
                'invoice_date' => $this->invoice_date,
                'total_amount' => $this->total_amount,
                'status' => 'unpaid'
            ]);

            foreach ($this->invoiceItems as $item) {
                $itemModel = Item::findOrFail($item['item_id']);

                if ($itemModel->stock < $item['quantity']) {
                    throw new Exception("Insufficient stock for item: " . $itemModel->name);
                }

                $itemModel->decrement('stock', $item['quantity']);
                $invoice->invoiceItems()->create($item);
            }

            foreach ($this->customItems as $item) {
                $invoice->invoiceItems()->create($item);
            }

            DB::commit();
            return $invoice;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
