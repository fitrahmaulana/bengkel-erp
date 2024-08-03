<?php

namespace App\Livewire;

use App\Models\Customer;
use Livewire\Component;

class CustomerManager extends Component
{
    public $customers, $name, $email, $phone, $address, $customer_id;
    public $isOpen = false;

    public function render()
    {
        $this->customers = Customer::all();
        return view('livewire.customer-manager')
                ->title('Manajemen Pelanggan');
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
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->customer_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:customers,email,' . $this->customer_id,
            'phone' => 'required',
            'address' => 'required',
        ]);

        Customer::updateOrCreate(['id' => $this->customer_id], [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        $this->dispatch('flash-message', type: 'success', message: $this->customer_id ? 'Pelanggan Diperbarui.' : 'Pelanggan Ditambahkan.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $this->customer_id = $id;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->address = $customer->address;
        $this->openModal();
    }

    public function delete($id)
    {
        Customer::find($id)->delete();
        $this->dispatch('flash-message', type: 'success', message: 'Pelanggan Dihapus.');
    }
}
