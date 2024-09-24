<?php

namespace App\Livewire\Invoices;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Livewire\Component;

use function PHPSTORM_META\type;

class EditInvoice extends Component
{
    public $invoice;
    public $invoiceItems = [];
    public $customers;
    public $customer_id, $invoice_date, $total_amount;

    public function mount($invoiceId)
    {
        $this->invoice = Invoice::findOrFail($invoiceId);
        $this->invoiceItems = InvoiceItem::where('invoice_id', $this->invoice->id)->get()->toArray();
        $this->customers = Customer::all();
        $this->customer_id = $this->invoice->customer_id;
        $this->invoice_date = $this->invoice->invoice_date;
        $this->total_amount = $this->invoice->total_amount;
    }

    public function render()
    {
        return view('livewire.invoices.edit-invoice', [
            'invoice' => $this->invoice,
            'invoiceItems' => $this->invoiceItems,
            'customers' => $this->customers,
        ]);
    }

    public function update()
    {
        $this->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
        ]);

        $this->invoice->update([
            'customer_id' => $this->customer_id,
            'invoice_date' => $this->invoice_date,
            'total_amount' => $this->total_amount,
        ]);

        foreach ($this->invoiceItems as $item) {
            $invoiceItem = InvoiceItem::findOrFail($item['id']);
            $invoiceItem->update([
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);
        }

        $this->dispatch('flash-message', type: 'success', message: 'Faktur berhasil diperbarui!');
        return redirect()->route('invoices.index');
    }
}
