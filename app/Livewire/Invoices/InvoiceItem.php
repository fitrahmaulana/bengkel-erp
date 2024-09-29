<?php

namespace App\Livewire\Invoices;

use App\Models\Item;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class InvoiceItem extends Component
{
    public $item;
    public $index;

    public $quantity;
    public function mount($item, $index)
    {
        $this->item = $item;
        $this->index = $index;
        $this->quantity = $item['quantity'];
    }

    public function updatedQuantity($value)
    {
        $this->resetErrorBag();
        $stock = Item::find($this->item['item_id'])->stock;
        $numericValue = floatval($value);

        if ($numericValue < 0) {
            $this->addError("quantity", "Kuantitas tidak boleh negatif.");
            return;
        }

        if ($numericValue > $stock) {
            $this->addError("quantity", "Kuantitas melebihi stok yang tersedia ({$stock}).");
            return;
        }

        $this->item['quantity'] = $numericValue;
        $this->item['total'] = $this->item['price'] * $numericValue;
        $this->dispatch('invoice-item-updated', index: $this->index, quantity: $numericValue);
    }

    public function remove()
    {
        $this->dispatch('invoice-item-removed', index: $this->index);
    }

    public function render()
    {
        return view('livewire.invoices.invoice-item', [
            'stock' => Item::find($this->item['item_id'])->stock,
        ]);
    }
}
