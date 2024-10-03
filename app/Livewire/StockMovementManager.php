<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StockMovement;
use App\Models\Item;
use App\Models\Supplier;

class StockMovementManager extends Component
{
    public $stockMovements, $items, $suppliers;
    public $item_id, $quantity, $type, $supplier_id;
    public $isOpen = false;

    public function render()
    {
        $this->stockMovements = StockMovement::with('item', 'supplier')->get();
        $this->items = Item::all();
        return view('livewire.stock-movement-manager')
            ->title('Manajemen Pergerakan Stok');
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
        $this->item_id = $this->quantity = $this->type = $this->supplier_id = '';
    }

    public function store()
    {
        $this->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:in,out',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $item = Item::findOrFail($this->item_id);

        if ($this->type == 'in') {
            $item->stock += $this->quantity;
        } elseif ($this->type == 'out') {
            if ($item->stock < $this->quantity) {
                $this->dispatch('flash-message', type: 'error', message: 'Stok barang tidak mencukupi.');
                return;
            }
            $item->stock -= $this->quantity;
        }

        $item->save();

        StockMovement::create([
            'item_id' => $this->item_id,
            'quantity' => $this->quantity,
            'type' => $this->type,
        ]);

        $this->dispatch('flash-message', type: 'success', message: 'Pergerakan stok berhasil disimpan.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        $movement = StockMovement::findOrFail($id);
        $item = Item::findOrFail($movement->item_id);

        if ($movement->type == 'in') {
            $item->stock -= $movement->quantity;
        } elseif ($movement->type == 'out') {
            $item->stock += $movement->quantity;
        }

        $item->save();
        $movement->delete();

        $this->dispatch('flash-message', type: 'success', message: 'Pergerakan stok dihapus.');
    }
}
