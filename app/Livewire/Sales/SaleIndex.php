<?php

namespace App\Livewire\Sales;

use App\Livewire\Traits\WithPerPagePagination;
use App\Livewire\Traits\WithUniversalSearch;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;

class SaleIndex extends Component
{
    use WithPagination;

    public $deleteId;

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        $sale = Sale::find($this->deleteId);

        // Restore stock
        foreach ($sale->items as $item) {
            $item->product->increment('stock_qty', $item->qty);
        }

        $sale->delete();
        session()->flash('success','Sale deleted successfully.');
    }

    public function render()
    {
        return view('livewire.sales.sale-index', [
            'sales' => Sale::with('customer','invoice')->latest()->paginate(10)
        ]);
    }
}
