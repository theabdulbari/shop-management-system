<?php

namespace App\Livewire\Purchase;

use App\Livewire\Traits\WithPerPagePagination;
use App\Livewire\Traits\WithUniversalSearch;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Purchase;

class PurchaseIndex extends Component
{
    use WithPagination;

    public $deleteId;

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        $purchase = Purchase::find($this->deleteId);

        // Optional: reduce stock
        foreach ($purchase->items as $item) {
            $item->product->decrement('stock_qty', $item->quantity);
        }

        $purchase->delete();
        session()->flash('success', 'Purchase deleted successfully.');
    }

    public function render()
    {
        return view('livewire.purchase.purchase-index', [
            'purchases' => Purchase::with('items', 'supplier')->latest()->paginate(10)
        ]);
    }
}
