<?php

namespace App\Livewire\Invoices;

use App\Livewire\Forms\InvoiceForm;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Item;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class CreateInvoice extends Component
{
    public InvoiceForm $form;
    public $searchTerm = '';
    public $quantity = 1;

    public function mount()
    {
        $this->form->fill([
            'invoice_date' => now()->format('Y-m-d'),
            'invoiceItems' => [],
            'customItems' => [],
        ]);
    }

    public function render()
    {
        return view('livewire.invoices.create-invoice', [
            'customers' => $this->customers,
            'items' => $this->searchResults,
        ])->title('Buat Faktur Baru');
    }

    #[Computed]
    public function customers()
    {
        return Customer::all();
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
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
        if ($this->quantity > $item->stock) {
            $this->addError('quantity', "Kuantitas melebihi stok yang tersedia ({$item->stock}).");
            return;
        }

        $this->form->addInvoiceItem($item, $this->quantity);
        $this->reset(['searchTerm', 'quantity']);
    }

    public function addCustomItem()
    {
        $this->validate([
            'form.customItemName' => 'required',
            'form.customItemQuantity' => 'required|numeric|min:1',
            'form.customItemPrice' => 'required|numeric|min:0',
            'form.customItemType' => 'required|in:service,custom',
        ]);

        $this->form->addCustomItem();
        $this->form->reset(['customItemName', 'customItemQuantity', 'customItemPrice', 'customItemType']);
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
        $this->form->validate();
        $this->form->store();

        $this->dispatch('flash-message', type: 'success', message: 'Faktur berhasil disimpan.');
        return $this->redirect(route('invoices.index'), navigate: true);
    }
}
