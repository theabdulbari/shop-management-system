<?php

namespace App\Livewire\Stock;

use App\Livewire\Traits\WithPerPagePagination;
use App\Livewire\Traits\WithUniversalSearch;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class StockIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $category_id;

    public function render()
    {
        $query = Product::query();

        if($this->search){
            $query->where('name','like','%'.$this->search.'%')
                  ->orWhere('sku','like','%'.$this->search.'%');
        }

        if($this->category_id){
            $query->where('category_id', $this->category_id);
        }

        $products = $query->with('category')->paginate(10);

        $totalStockValue = $products->sum(function($p){
            return $p->stock_qty * $p->purchase_price;
        });

        $lowStockCount = $products->where('stock_qty', '<=', 'stock_alert_qty')->count();

        return view('livewire.stock.stock-index', [
            'products' => $products,
            'totalStockValue' => $totalStockValue,
            'lowStockCount' => $lowStockCount
        ]);
    }
}
