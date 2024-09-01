<?php

// Livewire Component: InvoiceManager.php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Item;
use App\Models\InvoiceItem;
use phpDocumentor\Reflection\Types\This;

class InvoiceManager extends Component
{
    public $invoices, $customers, $items = [];
    public $searchTerm;
    public $selectedItem, $quantity = 1;
    public $invoiceItems = [];
    public $customer_id, $invoice_date, $total_amount = 0, $status = 'unpaid';
    public $isOpen = false;

    public function render()
    {
        $this->invoices = Invoice::with('customer', 'items.item')->get();
        $this->customers = Customer::all();

        // Search items based on search term
        if (!empty($this->searchTerm)) {
            $this->items = Item::where('name', 'like', '%' . $this->searchTerm . '%')->get();
        } else {
            $this->items = [];
        }

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
        $this->customer_id = $this->invoice_date = $this->total_amount = '';
        $this->invoiceItems = [];
        $this->total_amount = 0;
    }

    public function selectItem($itemId)
    {
        $item = Item::find($itemId);

        if ($item) {
            $this->selectedItem = $item;

            // Cek stok di database
            if ($this->quantity > $item->quantity) {
                $this->dispatch('flash-message', type: 'error', message: 'Stok tidak mencukupi.');
            } else {
                $this->addInvoiceItem();
            }
        }

        // Reset search term setelah item dipilih
        $this->reset('searchTerm');
    }

    public function addInvoiceItem()
    {
        $this->invoiceItems[] = [
            'item_id' => $this->selectedItem->id,
            'name' => $this->selectedItem->name,
            'quantity' => $this->quantity,
            'price' => $this->selectedItem->price,
            'total' => $this->selectedItem->price * $this->quantity,
        ];

        $this->calculateTotal();
    }


    public function removeInvoiceItem($index)
    {
        unset($this->invoiceItems[$index]);
        $this->invoiceItems = array_values($this->invoiceItems);
        $this->calculateTotal();
    }

    public function updated($propertyName, $value)
    {
        // Clear specific field error
        $this->resetErrorBag($propertyName);

        if (preg_match('/^invoiceItems\.(\d+)\.quantity$/', $propertyName, $matches)) {
            $index = $matches[1];
            $item = &$this->invoiceItems[$index];

            // Cek stok di database
            $stock = Item::find($item['item_id'])->quantity;

            // Pastikan $value adalah numerik, konversi jika perlu
            $numericValue = floatval($value); // Menggunakan floatval untuk mengakomodasi nilai pecahan

            // Tambahkan pemeriksaan untuk nilai negatif
            if ($numericValue < 0) {
                $this->addError("invoiceItems.{$index}.quantity", "Kuantitas tidak boleh negatif.");
                return; // Hentikan eksekusi lebih lanjut jika nilai negatif
            }

            if ($numericValue > $stock) {
                // Jika kuantitas melebihi stok, tampilkan pesan error
                $this->addError("invoiceItems.{$index}.quantity", "Kuantitas melebihi stok yang tersedia ({$stock}).");
            } else {
                // Pastikan 'price' juga diubah menjadi numerik sebelum operasi perkalian
                $numericPrice = floatval($item['price']);

                // Jika kuantitas valid, perbarui total untuk item
                $item['total'] = $numericPrice * $numericValue;
                $this->calculateTotal();
            }
        }
    }

    public function calculateTotal()
    {
        $this->total_amount = array_sum(array_column($this->invoiceItems, 'total'));
    }

    public function store()
    {
        $this->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'invoiceItems.*.item_id' => 'required|exists:items,id',
            'invoiceItems.*.quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attr, $value, $fail) {
                    // Dapatkan index dari invoiceItems
                    $index = explode('.', $attr)[1];
                    $item = $this->invoiceItems[$index];
                    $stock = Item::find($item['item_id'])->quantity;

                    if ($value > $stock) {
                        $fail("Kuantitas untuk {$item['name']} melebihi stok yang tersedia ({$stock}).");
                    }
                }
            ],
        ]);

        $invoice = Invoice::create([
            'customer_id' => $this->customer_id,
            'invoice_date' => $this->invoice_date,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
        ]);

        foreach ($this->invoiceItems as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total'],
            ]);

            // Mengurangi quantity pada model Item
            $itemModel = Item::find($item['item_id']);
            if ($itemModel) {
                $itemModel->quantity -= $item['quantity'];
                $itemModel->save();
            }
        }

        $this->dispatch('flash-message', type: 'success', message: 'Faktur berhasil disimpan.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function clearError($propertyName)
    {
        $this->resetErrorBag($propertyName);
    }

    public function delete($id)
    {
        Invoice::find($id)->delete();
        $this->dispatch('flash-message', type: 'success', message: 'Faktur berhasil dihapus.');
    }
}
