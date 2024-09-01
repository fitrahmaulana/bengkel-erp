<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Invoice;
use Carbon\Carbon;

class SalesReport extends Component
{
    public $startDate, $endDate;
    public $salesData;

    public function render()
    {
        $this->salesData = Invoice::whereBetween('invoice_date', [$this->startDate, $this->endDate])
                                  ->selectRaw('DATE(invoice_date) as date, SUM(total_amount) as total_sales')
                                  ->groupBy('date')
                                  ->get();

        return view('livewire.sales-report')
                ->title('Laporan Penjualan');
    }

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }
}
