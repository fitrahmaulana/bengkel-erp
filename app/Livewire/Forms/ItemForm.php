<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Item;

class ItemForm extends Form
{
    public ?int $item_id = null;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('required|min:0')]
    public $stock = 0;

    #[Validate('required|numeric|min:0')]
    public $price = 0;

    #[Validate('required|min:0')]
    public $min_stock = 0;

    #[Validate('required|exists:suppliers,id')]
    public $supplier_id;

    public function setItem(Item $item)
    {
        $this->item_id = $item->id;
        $this->name = $item->name;
        $this->description = $item->description;
        $this->stock = $item->stock;
        $this->price = $item->price;
        $this->min_stock = $item->min_stock;
        $this->supplier_id = $item->supplier_id;
    }

    public function save()
    {
        $this->validate();
        return Item::updateOrCreate(['id' => $this->item_id], $this->all());
    }
}
