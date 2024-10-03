<?php

namespace App\Livewire\Items;

use Livewire\Component;
use App\Models\Item;
use App\Models\Supplier;

class ItemManager extends Component
{
    protected $listeners = ['item-saved' => 'handleSavedItem'];

    public function render()
    {
        return view('livewire.items.item-manager', [
            'items' => Item::all(),
        ])->title('Manajemen Barang');
    }

    public function create()
    {
        $this->dispatch('openModal');
    }

    public function edit($id)
    {
        $this->dispatch('openModal', itemId: $id);
    }

    public function handleSavedItem($itemId)
    {
        $item = Item::findOrFail($itemId);
        $message = $item->wasRecentlyCreated ? 'Item Created Successfully.' : 'Item Updated Successfully.';
        $this->dispatch('flash-message', type: 'success', message: $message);
    }

    public function delete($id)
    {
        Item::find($id)->delete();
        $this->dispatch('flash-message', type: 'success', message: 'Item Deleted Successfully.');
    }

    public function checkLowStock()
    {
        $lowStockItems = Item::whereColumn('stock', '<=', 'min_stock')->get();
        foreach ($lowStockItems as $item) {
            // Trigger notification or other actions
        }
    }
}
