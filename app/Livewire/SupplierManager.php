<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Supplier;

class SupplierManager extends Component
{
    public $suppliers, $name, $contact_person, $phone, $address, $supplier_id;
    public $isOpen = false;

    public function render()
    {
        $this->suppliers = Supplier::all();
        return view('livewire.supplier-manager')
            ->title('Manajemen Pemasok');
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
        $this->name = $this->contact_person = $this->phone = $this->address = $this->supplier_id = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'contact_person' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        Supplier::updateOrCreate(['id' => $this->supplier_id], [
            'name' => $this->name,
            'contact_person' => $this->contact_person,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        $this->dispatch('flash-message', type: 'success', message: 'Pemasok berhasil disimpan.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->supplier_id = $id;
        $this->name = $supplier->name;
        $this->contact_person = $supplier->contact_person;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;

        $this->openModal();
    }

    public function delete($id)
    {
        Supplier::find($id)->delete();
        $this->dispatch('flash-message', type: 'success', message: 'Pemasok berhasil dihapus.');
    }
}
