<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Supplier;

class SupplierManager extends Component
{
    public $suppliers, $name, $contact_info, $supplier_id;
    public $isOpen = false;

    public function render()
    {
        $this->suppliers = Supplier::all();
        return view('livewire.supplier-manager');
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

    private function resetInputFields()
    {
        $this->name = '';
        $this->contact_info = '';
        $this->supplier_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'contact_info' => 'required',
        ]);

        Supplier::updateOrCreate(['id' => $this->supplier_id], [
            'name' => $this->name,
            'contact_info' => $this->contact_info,
        ]);

        session()->flash('message', $this->supplier_id ? 'Supplier Updated Successfully.' : 'Supplier Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->supplier_id = $id;
        $this->name = $supplier->name;
        $this->contact_info = $supplier->contact_info;

        $this->openModal();
    }

    public function delete($id)
    {
        Supplier::find($id)->delete();
        session()->flash('message', 'Supplier Deleted Successfully.');
    }
}
