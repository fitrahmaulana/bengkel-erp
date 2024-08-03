<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Invoice;
use Livewire\Component;

class InvoiceManager extends Component
{
    public $invoices, $customers, $customer_id, $invoice_date, $total_amount, $status, $invoice_id;
    public $isOpen = false;

    public function render()
    {
        $this->invoices = Invoice::all();
        $this->customers = Customer::all();
        return view('livewire.invoice-manager')
            ->title('Manajemen Faktur');
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
        $this->customer_id = $this->invoice_date = $this->total_amount = $this->status = $this->invoice_id = '';
    }

    public function store()
    {
        $this->validate([
            'customer_id' => 'required',
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'status' => 'required'
        ]);

        Invoice::updateOrCreate(['id' => $this->invoice_id], [
            'customer_id' => $this->customer_id,
            'invoice_date' => $this->invoice_date,
            'total_amount' => $this->total_amount,
            'status' => $this->status
        ]);

        $this->dispatch('flash-message', [
            'type' => 'success',
            'message' => $this->invoice_id ? 'Faktur diperbarui.' : 'Faktur ditambahkan.'
        ]);
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $this->invoice_id = $id;
        $this->customer_id = $invoice->customer_id;
        $this->invoice_date = $invoice->invoice_date;
        $this->total_amount = $invoice->total_amount;
        $this->status = $invoice->status;

        $this->openModal();
    }

    public function delete($id)
    {
        Invoice::find($id)->delete();
        $this->dispatch('flash-message', [
            'type' => 'success',
            'message' => 'Faktur dihapus.'
        ]);
    }
}
