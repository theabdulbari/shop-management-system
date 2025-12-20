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

    public $search = '';
    public $fromDate;
    public $toDate;
    public $paymentStatus = ''; // paid | due | partial

    public $perPage = 25;

    protected $queryString = [
        'search' => ['except' => ''],
        'fromDate' => ['except' => ''],
        'toDate' => ['except' => ''],
        // 'paymentStatus' => ['except' => ''],
        'perPage' => ['except' => 25],
    ];

    public function resetFilters()
    {
        $this->reset([
            'search',
            'fromDate',
            'toDate',
            'paymentStatus',
        ]);

        $this->perPage = 25;
        $this->resetPage();
    }

     public function updating($field)
    {
        if (in_array($field, [
            'search', 'fromDate', 'toDate', 'paymentStatus', 'perPage'
        ])) {
            $this->resetPage();
        }
    }

    public function updatingFromDate()
    {
        $this->resetPage();
    }

    public function updatingToDate()
    {
        $this->resetPage();
    }

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

        $query = Purchase::with([
        'supplier:id,name',
        // 'invoice:id,purchase_id,invoice_number',
        'items.product:id,name'
    ])

    ->when($this->search, function ($q) {
        $search = $this->search;

        $q->where(function ($query) use ($search) {
            $query
                ->whereHas('supplier', fn ($s) =>
                    $s->where('name', 'like', "%{$search}%")
                )
                // ->orWhereHas('invoice', fn ($i) =>
                //     $i->where('invoice_number', 'like', "%{$search}%")
                // )
                ->orWhereHas('items.product', fn ($p) =>
                    $p->where('name', 'like', "%{$search}%")
        );
                // ->orWhere('paid', 'like', "%{$search}%")
                // ->orWhere('due', 'like', "%{$search}%");

            if (strtotime($search)) {
                $query->orWhereDate('purchase_date', $search);
            }
        });
    })

    // ->when($this->paymentStatus, function ($q) {
    //     if ($this->paymentStatus === 'paid') {
    //         $q->where('due', 0);
    //     } elseif ($this->paymentStatus === 'due') {
    //         $q->where('paid', 0);
    //     } else {
    //         $q->where('paid', '>', 0)->where('due', '>', 0);
    //     }
    // })

    ->when($this->fromDate, function ($q) {
        $q->whereDate('purchase_date', '>=', $this->fromDate);
    })
    ->when($this->toDate, function ($q) {
        $q->whereDate('purchase_date', '<=', $this->toDate);
    })

    ->latest();

    $purchases = $this->perPage === 'all'
        ? $query->get()
        : $query->paginate($this->perPage);




        return view('livewire.purchase.purchase-index', [
            'purchases' => $purchases
        ]);
    }
}
