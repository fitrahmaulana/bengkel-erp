<?php

namespace App\Livewire\Items;

use Livewire\Component;
use App\Livewire\Forms\ItemForm;
use App\Models\Item;
use App\Models\Supplier;
use Livewire\Attributes\On;

class ItemFormModal extends Component
{
    public ItemForm $form;
    public $isOpen = false;
    public $supplyers;

    public function render()
    {
        return view('livewire.items.item-form-modal', [
            'suppliers' => Supplier::all(),
        ]);
    }

    #[On('openModal')]
    public function openModal($itemId = null)
    {
        if ($itemId) {
            $item = Item::findOrFail($itemId);
            $this->form->setItem($item);
        } else {
            $this->form->reset();
        }
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->form->reset();
    }

    public function save()
    {
        $item = $this->form->save();
        $this->dispatch('item-saved', itemId: $item->id);
        $this->closeModal();
    }
}
