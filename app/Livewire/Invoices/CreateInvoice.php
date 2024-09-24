<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class CreateInvoice extends Component
{
    public $customers, $items = [];
    public $customer_id, $invoice_date, $total_amount = 0;
    public $invoiceItems = [], $customItems = [];
    public $searchTerm, $quantity = 1;
    public $customItemName, $customItemQuantity = 1, $customItemPrice, $customItemType;


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

    protected function messages(){
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

    public function mount()
    {
        $this->customers = Customer::all();
    }

    public function render()
    {
        if (!empty($this->searchTerm)) {
            $this->items = Item::where('name', 'like', '%' . $this->searchTerm . '%')->get();
        } else {
            $this->items = [];
        }

        return view('livewire.invoices.create-invoice')
        ->title('Buat Faktur Baru');
    }

    public function selectItem($itemId)
    {
        $item = Item::find($itemId);

        if ($item) {
            // Ensure quantity is not more than stock
            if ($this->quantity > $item->stock) {
                $this->addError('quantity', "Kuantitas melebihi stok yang tersedia ({$item->stock}).");
                return;
            }

            $this->invoiceItems[] = [
                'item_id' => $item->id,
                'name' => $item->name,
                'quantity' => $this->quantity,
                'price' => $item->price,
                'total' => $item->price * $this->quantity,
            ];
            $this->calculateTotal();
        }

        $this->reset('searchTerm', 'quantity');
    }

    public function addCustomItem()
    {
        $this->validate();
        $this->customItems[] = [
            'name' => $this->customItemName,
            'quantity' => $this->customItemQuantity,
            'price' => $this->customItemPrice,
            'total' => $this->customItemPrice * $this->customItemQuantity,
            'type' => $this->customItemType,
        ];
        $this->calculateTotal();
        $this->reset(['customItemName', 'customItemQuantity', 'customItemPrice']);
    }

    public function updated($propertyName, $value)
    {
        $this->resetErrorBag($propertyName);

        if (preg_match('/^invoiceItems\.(\d+)\.quantity$/', $propertyName, $matches)) {
            $index = $matches[1];
            $item = &$this->invoiceItems[$index];
            $stock = Item::find($item['item_id'])->stock;
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

    public function calculateTotal()
    {
        $this->total_amount = array_sum(array_column($this->invoiceItems, 'total')) + array_sum(array_column($this->customItems, 'total'));
    }

    public function store()
    {
        $this->validate();

        $invoice = Invoice::create([
            'customer_id' => $this->customer_id,
            'invoice_date' => $this->invoice_date,
            'total_amount' => $this->total_amount,
            'status' => 'unpaid',
        ]);

        // create invoice items barang
        foreach ($this->invoiceItems as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_id' => $item['item_id'],
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'type' => 'product',
                'total' => $item['total'],
            ]);

            // Reduce item stock
            $itemModel = Item::find($item['item_id']);
            if ($itemModel) {
                $itemModel->stock -= $item['quantity'];
                $itemModel->save();
            }
        }

        // create invoice items custom
        foreach ($this->customItems as $customItem) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_id' => null,
                'name' => $customItem['name'],
                'quantity' => $customItem['quantity'],
                'price' => $customItem['price'],
                'total' => $customItem['total'],
                'name' => $customItem['name'],
                'type' => $customItem['type'],
            ]);
        }

        $this->dispatch('flash-message', type: 'success', message: 'Faktur berhasil disimpan.');
        $this->reset(['customer_id', 'invoice_date', 'invoiceItems', 'customItems', 'total_amount']);
        return redirect()->route('invoices.index');
    }
}
