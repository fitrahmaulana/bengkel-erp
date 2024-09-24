<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Livewire\Component;

class ShowInvoice extends Component
{

    public $invoice;
    public $invoiceItems = [];

    public function mount($invoiceId)
    {
        $this->invoice = Invoice::with('customer')->findOrFail($invoiceId);
        $this->invoiceItems = InvoiceItem::where('invoice_id', $this->invoice->id)->get();
    }

    public function render()
    {
        return view('livewire.invoices.show-invoice');
    }
}
