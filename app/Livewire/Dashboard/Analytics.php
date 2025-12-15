<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\StockTransaction;
use Carbon\Carbon;

class Analytics extends Component
{
    public $dailySales = 0;
    public $weeklySales = 0;
    public $monthlySales = 0;
    public $totalProducts = 0;
    public $totalStockValue = 0;
    public $lowStockCount = 0;

    public function mount()
    {
        $this->calculateMetrics();
    }

    public function calculateMetrics()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        $this->dailySales = Sale::whereDate('created_at', $today)->sum('grand_total');
        $this->weeklySales = Sale::where('created_at', '>=', $thisWeek)->sum('grand_total');
        $this->monthlySales = Sale::where('created_at', '>=', $thisMonth)->sum('grand_total');

        $products = Product::all();
        $this->totalProducts = $products->count();
        $this->totalStockValue = $products->sum(function($p){
            return $p->stock_qty * $p->purchase_price;
        });
        $this->lowStockCount = $products->where('stock_qty', '<=', 'stock_alert_qty')->count();
    }

    public function getDailySalesProperty()
    {
        return Sale::whereDate('created_at', today())->sum('grand_total');
    }

    public function getWeeklySalesProperty()
    {
        return Sale::where('created_at', '>=', now()->startOfWeek())->sum('grand_total');
    }

    public function getMonthlySalesProperty()
    {
        return Sale::where('created_at', '>=', now()->startOfMonth())->sum('grand_total');
    }

    public function getTotalStockValueProperty()
    {
        return Product::sum(function($p){ return $p->stock_qty * $p->purchase_price; });
    }

    public function getLowStockCountProperty()
    {
        return Product::whereColumn('stock_qty', '<=', 'stock_alert_qty')->count();
    }


    public function render()
    {
        return view('livewire.dashboard.analytics');
    }
}
