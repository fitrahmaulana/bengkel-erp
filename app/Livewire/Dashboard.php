<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Item;

class Dashboard extends Component
{
    public $totalInvoices;
    public $totalSales;
    public $unpaidInvoices;
    public $totalCustomers;
    public $newCustomers;
    public $lowStockItems;
    public $totalItems;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Menghitung total faktur
        $this->totalInvoices = Invoice::count();

        // Menghitung total penjualan (jumlah total dari semua faktur yang dibayar)
        $this->totalSales = Invoice::where('status', 'paid')->sum('total_amount');

        // Menghitung faktur yang belum dibayar
        $this->unpaidInvoices = Invoice::where('status', 'unpaid')->count();

        // Menghitung total pelanggan
        $this->totalCustomers = Customer::count();

        // Menghitung pelanggan baru (misalnya, dalam 30 hari terakhir)
        $this->newCustomers = Customer::where('created_at', '>=', now()->subDays(30))->count();

        // Menghitung barang dengan stok rendah (misalnya, stok di bawah 10)
        $this->lowStockItems = Item::where('quantity', '<', 10)->count();

        // Menghitung total item
        $this->totalItems = Item::count();
    }

    public function render()
    {
        return view('livewire.Dashboard')->title('Dashboard');
    }
}
