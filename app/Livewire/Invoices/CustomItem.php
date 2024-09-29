<?php

namespace App\Livewire\Invoices;

use Livewire\Component;

class CustomItem extends Component
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
        $numericValue = floatval($value);

        if ($numericValue < 0) {
            $this->addError("quantity", "Kuantitas tidak boleh negatif.");
            return;
        }

        $this->item['quantity'] = $numericValue;
        $this->item['total'] = $this->item['price'] * $numericValue;
        $this->dispatch('custom-item-updated', index: $this->index, quantity: $numericValue);
    }

    public function remove()
    {
        $this->dispatch('custom-item-removed', index: $this->index);
    }

    public function render()
    {
        return view('livewire.invoices.custom-item');
    }
}
