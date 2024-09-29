<?php

namespace App\Livewire\Invoices;

use App\Livewire\Forms\InvoiceForm;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Item;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class EditInvoice extends Component
{
    public InvoiceForm $form;
    public Invoice $invoice;
    public $searchTerm = '';
    public $quantity = 1;

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->form->setInvoice($invoice);
    }

    public function render()
    {
        return view('livewire.invoices.edit-invoice', [
            'customers' => $this->customers,
            'items' => $this->searchResults,
        ])->title('Edit Invoice');
    }

    #[Computed]
    public function customers()
    {
        return Customer::all();
    }

    #[Computed]
    public function searchResults()
    {
        return strlen($this->searchTerm) > 2
            ? Item::where('name', 'like', "%{$this->searchTerm}%")->get()
            : collect();
    }

    public function selectItem(Item $item)
    {
        $this->validate([
            'quantity' => "required|integer|min:1|max:{$item->stock}",
        ]);

        $this->form->addInvoiceItem($item, $this->quantity);
        $this->reset(['searchTerm', 'quantity']);
    }

    public function addCustomItem()
    {
        $this->form->addCustomItem();
    }

    #[On('invoice-item-updated')]
    public function handleItemUpdated($index, $quantity)
    {
        $this->form->updateInvoiceItem($index, $quantity);
    }

    #[On('invoice-item-removed')]
    public function removeInvoiceItem($index)
    {
        $this->form->removeInvoiceItem($index);
    }

    #[On('custom-item-updated')]
    public function handleCustomItemUpdated($index, $quantity)
    {
        $this->form->updateCustomItem($index, $quantity);
    }

    #[On('custom-item-removed')]
    public function removeCustomItem($index)
    {
        $this->form->removeCustomItem($index);
    }

    public function save()
    {
        try {
            $this->form->update();
            $this->dispatch('notify', type: 'success', message: 'Invoice updated successfully.');
            return $this->redirect(route('invoices.index'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Failed to update invoice: ' . $e->getMessage());
        }
    }
}
