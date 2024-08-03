<?php

namespace App\Livewire;

use App\Models\Invoice;
use Carbon\Carbon;
use Livewire\Component;

class SalesReport extends Component
{
    public $start_date, $end_date, $invoices;

    public function render()
    {
        $this->invoices = Invoice::whereBetween('invoice_date', [$this->start_date, $this->end_date])->get();
        return view('livewire.sales-report')
                ->title('Laporan Penjualan');
    }

    public function mount()
    {
        $this->start_date = Carbon::now()->startOfMonth()->toDateString();
        $this->end_date = Carbon::now()->endOfMonth()->toDateString();
    }
}
