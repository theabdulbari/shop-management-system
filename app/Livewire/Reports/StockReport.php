<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class StockReport extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $low_stock_only = false;

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->category) {
            $query->where('category_id', $this->category);
        }

        if ($this->low_stock_only) {
            $query->where('stock', '<=', 5);
        }

        $products = $query->paginate(10);

        $total_value = Product::sum(DB::raw('stock * price'));

        return view('livewire.reports.stock-report', compact('products', 'total_value'));
    }
}
