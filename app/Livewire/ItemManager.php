<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Models\StockMovement;

class ItemManager extends Component
{
    public $items, $name, $description, $quantity, $price, $min_stock, $item_id;
    public $isOpen = false;

    public function render()
    {
        $this->items = Item::all();
        return view('livewire.item-manager')
                ->title('Manajemen Barang');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->quantity = '';
        $this->price = '';
        $this->min_stock = '';
        $this->item_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'min_stock' => 'required|integer',
        ]);

        $item = Item::updateOrCreate(['id' => $this->item_id], [
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'min_stock' => $this->min_stock,
        ]);

        // Log stock movement
        if ($this->item_id) {
            $old_quantity = Item::find($this->item_id)->quantity;
            $difference = $this->quantity - $old_quantity;
            $type = $difference > 0 ? 'in' : 'out';

            StockMovement::create([
                'item_id' => $item->id,
                'type' => $type,
                'quantity' => abs($difference),
            ]);
        }

        $this->dispatch('flash-message', type: 'success', message: $this->item_id ? 'Item Updated Successfully.' : 'Item Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $this->item_id = $id;
        $this->name = $item->name;
        $this->description = $item->description;
        $this->quantity = $item->quantity;
        $this->price = $item->price;
        $this->min_stock = $item->min_stock;

        $this->openModal();
    }

    public function delete($id)
    {
        Item::find($id)->delete();

        $this->dispatch('flash-message', type: 'success', message: 'Item Deleted Successfully.');
    }

    public function checkLowStock()
    {
        $lowStockItems = Item::where('quantity', '<=', 'min_stock')->get();
        foreach ($lowStockItems as $item) {
            // Trigger notification or any other action
        }
    }
}
