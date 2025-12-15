<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Product;

class LowStockAlert extends Component
{
    public $products = [];

    public function mount()
    {
        $this->products = Product::whereColumn('stock_qty', '<=', 'stock_alert_qty')
            ->orderBy('stock_qty')
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.low-stock-alert');
    }
}
