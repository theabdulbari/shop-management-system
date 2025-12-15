<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Sale;
use Carbon\Carbon;

class SalesReport extends Component
{
    public $start_date;
    public $end_date;
    public $sales = [];

    public $total_profit = 0;

    public $chart_labels = [];
    public $chart_sales = [];
    public $chart_profit = [];

    public function mount()
    {
        // Default: Today
        $this->start_date = Carbon::today()->format('Y-m-d');
        $this->end_date   = Carbon::today()->format('Y-m-d');

        $this->loadSales();
    }

    public function updatedStartDate()
    {
        $this->loadSales();
    }

    public function updatedEndDate()
    {
        $this->loadSales();
    }

    public function setFilter($type)
    {
        switch ($type) {
            case 'today':
                $this->start_date = $this->end_date = Carbon::today()->format('Y-m-d');
                break;

            case '7days':
                $this->start_date = Carbon::now()->subDays(7)->format('Y-m-d');
                $this->end_date = Carbon::today()->format('Y-m-d');
                break;

            case '15days':
                $this->start_date = Carbon::now()->subDays(15)->format('Y-m-d');
                $this->end_date = Carbon::today()->format('Y-m-d');
                break;

            case '1month':
                $this->start_date = Carbon::now()->subMonth()->format('Y-m-d');
                $this->end_date = Carbon::today()->format('Y-m-d');
                break;

            case '3months':
                $this->start_date = Carbon::now()->subMonths(3)->format('Y-m-d');
                $this->end_date = Carbon::today()->format('Y-m-d');
                break;

            case '6months':
                $this->start_date = Carbon::now()->subMonths(6)->format('Y-m-d');
                $this->end_date = Carbon::today()->format('Y-m-d');
                break;
        }

        $this->loadSales();
    }

    // public function loadSales()
    // {
    //     $this->sales = Sale::whereDate('created_at', '>=', $this->start_date)
    //         ->whereDate('created_at', '<=', $this->end_date)
    //         ->orderBy('created_at', 'desc')
    //         ->get();
    // }

    public function loadSales()
    {
        $this->sales = Sale::with('sale_items.product')
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->calculateProfit();
        $this->prepareChartData();
    }

    public function calculateProfit()
    {
        $profit = 0;

        foreach ($this->sales as $sale) {
            foreach ($sale->sale_items as $item) {
                $cost  = $item->product->purchase_price * $item->qty;
                $sell  = $item->unit_price * $item->qty;
                $profit += ($sell - $cost);
            }
        }

        $this->total_profit = $profit;
    }

    public function prepareChartData()
    {
        $grouped = collect($this->sales)->groupBy(function ($sale) {
            return $sale->created_at->format('Y-m-d');
        });

        $this->chart_labels = [];
        $this->chart_sales = [];
        $this->chart_profit = [];

        foreach ($grouped as $date => $items) {

            $this->chart_labels[] = $date;

            $total = $items->sum('grand_total');

            $profit = 0;
            foreach ($items as $sale) {
                foreach ($sale->sale_items as $item) {
                    $profit += ($item->unit_price - $item->product->purchase_price) * $item->qty;
                }
            }

            $this->chart_sales[] = $total;
            $this->chart_profit[] = $profit;
        }

        $this->emit('refreshChart', [
            'labels' => $this->chart_labels,
            'sales' => $this->chart_sales,
            'profit' => $this->chart_profit,
        ]);
    }


    public function render()
    {
        return view('livewire.reports.sales-report');
    }
}
