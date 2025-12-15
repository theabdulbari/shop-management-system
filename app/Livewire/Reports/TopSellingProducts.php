<?php

namespace App\Livewire\Reports;


use Livewire\Component;
use App\Models\SaleItem;
use Carbon\Carbon;

class TopSellingProducts extends Component
{
    public $start_date;
    public $end_date;
    public $results = [];

    public function mount()
    {
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');

        $this->loadReport();
    }

    public function loadReport()
    {
        $this->results = SaleItem::with('product')
            ->whereHas('sale', function ($q) {
                $q->whereDate('created_at', '>=', $this->start_date)
                    ->whereDate('created_at', '<=', $this->end_date);
            })
            ->selectRaw('product_id, SUM(qty) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->get();
    }

    public function render()
    {
        return view('livewire.reports.top-selling-products');
    }
}
