<?php

namespace App\Livewire\Invoices;

use App\Livewire\Forms\InvoiceForm;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class CreateInvoice extends Component
{
    public InvoiceForm $form;
    // untuk menampilkan data pelanggan dan item di search bar
    public $customers, $items = [];
    public $searchTerm, $quantity = 1;

    public $customItemName, $customItemQuantity = 1, $customItemPrice, $customItemType;

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

            $this->form->invoiceItems[] = [
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
        $this->form->customItems[] = [
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

        if (preg_match('/^form.invoiceItems\.(\d+)\.quantity$/', $propertyName, $matches)) {
            $index = $matches[1];
            $item = &$this->form->invoiceItems[$index];
            $stock = Item::find($item['item_id'])->stock;
            $numericValue = floatval($value);

            if ($numericValue < 0) {
                $this->addError("form.invoiceItems.{$index}.quantity", "Kuantitas tidak boleh negatif.");
                return;
            }

            if ($numericValue > $stock) {
                $this->addError("form.invoiceItems.{$index}.quantity", "Kuantitas melebihi stok yang tersedia ({$stock}).");
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
        unset($this->form->invoiceItems[$index]);
        $this->form->invoiceItems = array_values($this->invoiceItems);
        $this->calculateTotal();
    }

    public function removeCustomItem($index)
    {
        unset($this->form->customItems[$index]);
        $this->form->customItems = array_values($this->form->customItems);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->form->total_amount = array_sum(array_column($this->form->invoiceItems, 'total')) + array_sum(array_column($this->form->customItems, 'total'));
    }

    public function save()
    {
        $this->validate();

        $this->form->store();
        $this->dispatch('flash-message', type: 'success', message: 'Faktur berhasil disimpan.');
        return redirect()->route('invoices.index');
    }
}
