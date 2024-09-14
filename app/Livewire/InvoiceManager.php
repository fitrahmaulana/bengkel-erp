<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Item;
use App\Models\InvoiceItem;

class InvoiceManager extends Component
{
    public $invoices, $customers, $items = [];
    public $searchTerm, $selectedItem, $quantity = 1;
    public $invoiceItems = [], $customItems = [];
    public $customer_id, $invoice_date, $total_amount = 0, $status = 'unpaid';
    public $isOpen = false;
    public $customItemName, $customItemPrice, $customItemQuantity = 1;

    public function render()
    {
        $this->invoices = Invoice::with('customer', 'invoiceItems.item')->get();
        $this->customers = Customer::all();

        if (!empty($this->searchTerm)) {
            $this->items = Item::where('name', 'like', '%' . $this->searchTerm . '%')->get();
        } else {
            $this->items = [];
        }

        return view('livewire.invoice-manager')
            ->title('Manajemen Faktur');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function resetInputFields()
    {
        $this->customer_id = $this->invoice_date = '';
        $this->invoiceItems = [];
        $this->customItems = [];
        $this->total_amount = 0;
        $this->reset(['customItemName', 'customItemPrice', 'customItemQuantity']);
    }

    public function selectItem($itemId)
    {
        $item = Item::find($itemId);

        if ($item) {
            $this->selectedItem = $item;

            if ($this->quantity > $item->quantity) {
                $this->dispatch('flash-message', type: 'error', message: 'Stok tidak mencukupi.');
            } else {
                $this->addInvoiceItem();
            }
        }

        $this->reset('searchTerm');
    }

    public function addInvoiceItem()
    {
        $this->invoiceItems[] = [
            'item_id' => $this->selectedItem->id,
            'name' => $this->selectedItem->name,
            'quantity' => $this->quantity,
            'price' => $this->selectedItem->price,
            'total' => $this->calculateItemTotal($this->selectedItem->price, $this->quantity),
        ];

        $this->calculateTotal();
    }

    public function addCustomItem()
    {
        $this->customItems[] = [
            'name' => $this->customItemName,
            'quantity' => $this->customItemQuantity,
            'price' => $this->customItemPrice,
            'total' => $this->calculateItemTotal($this->customItemPrice, $this->customItemQuantity),
        ];

        $this->reset(['customItemName', 'customItemPrice', 'customItemQuantity']);
        $this->calculateTotal();
    }

    public function removeInvoiceItem($index)
    {
        unset($this->invoiceItems[$index]);
        $this->invoiceItems = array_values($this->invoiceItems);
        $this->calculateTotal();
    }

    public function removeCustomItem($index)
    {
        unset($this->customItems[$index]);
        $this->customItems = array_values($this->customItems);
        $this->calculateTotal();
    }

    public function updated($propertyName, $value)
    {
        $this->resetErrorBag($propertyName);

        if (preg_match('/^invoiceItems\.(\d+)\.quantity$/', $propertyName, $matches)) {
            $index = $matches[1];
            $item = &$this->invoiceItems[$index];
            $stock = Item::find($item['item_id'])->quantity;
            $numericValue = floatval($value);

            if ($numericValue < 0) {
                $this->addError("invoiceItems.{$index}.quantity", "Kuantitas tidak boleh negatif.");
                return;
            }

            if ($numericValue > $stock) {
                $this->addError("invoiceItems.{$index}.quantity", "Kuantitas melebihi stok yang tersedia ({$stock}).");
            } else {
                $item['total'] = floatval($item['price']) * $numericValue;
                $this->calculateTotal();
            }
        } elseif (preg_match('/^customItems\.(\d+)\.quantity$/', $propertyName, $matches)) {
            $index = $matches[1];
            $item = &$this->customItems[$index];
            $numericValue = floatval($value);
            $item['total'] = floatval($item['price']) * $numericValue;
            $this->calculateTotal();
        }
    }

    public function calculateTotal()
    {
        $this->total_amount = array_sum(array_column($this->invoiceItems, 'total')) + array_sum(array_column($this->customItems, 'total'));
    }

    public function store()
    {
        $this->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'invoiceItems.*.item_id' => 'required|exists:items,id',
            'invoiceItems.*.quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attr, $value, $fail) {
                    $index = explode('.', $attr)[1];
                    $item = $this->invoiceItems[$index];
                    $stock = Item::find($item['item_id'])->quantity;

                    if ($value > $stock) {
                        $fail("Kuantitas untuk {$item['name']} melebihi stok yang tersedia ({$stock}).");
                    }
                }
            ],
            'customItems.*.name' => 'required|string',
            'customItems.*.quantity' => 'required|integer|min:1',
            'customItems.*.price' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::create([
            'customer_id' => $this->customer_id,
            'invoice_date' => $this->invoice_date,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
        ]);

        foreach ($this->invoiceItems as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);

            $itemModel = Item::find($item['item_id']);
            if ($itemModel) {
                $itemModel->quantity -= $item['quantity'];
                $itemModel->save();
            }
        }

        foreach ($this->customItems as $customItem) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_id' => null,  // Indicates a custom item
                'quantity' => $customItem['quantity'],
                'price' => $customItem['price'],
                'total' => $customItem['total'],
                'name' => $customItem['name'], // Save custom name directly
            ]);
        }

        $this->dispatch('flash-message', type: 'success', message: 'Faktur berhasil disimpan.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function calculateItemTotal($price, $quantity)
    {
        return $price * $quantity;
    }
}
