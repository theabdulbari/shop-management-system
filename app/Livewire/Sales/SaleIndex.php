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
    public $expandedSaleId = null;
    public $search = '';
    public $fromDate;
    public $toDate;
    public $paymentStatus = ''; // paid | due | partial
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'fromDate' => ['except' => ''],
        'toDate' => ['except' => ''],
        'paymentStatus' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updating($field)
    {
        if (in_array($field, [
            'search', 'fromDate', 'toDate', 'paymentStatus', 'perPage'
        ])) {
            $this->resetPage();
        }
    }

    public function updatedPerPage($value)
{
    if ($value !== 'all') {
        $this->perPage = (int) $value;
    }

    $this->resetPage();
}

    public function resetFilters()
    {
        $this->reset([
            'search',
            'fromDate',
            'toDate',
            'paymentStatus',
        ]);

        $this->perPage = 10;
        $this->resetPage();
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFromDate()
    {
        $this->resetPage();
    }

    public function updatingToDate()
    {
        $this->resetPage();
    }

    public function toggleItems($saleId)
    {
        $this->expandedSaleId = $this->expandedSaleId === $saleId ? null : $saleId;
    }

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
        $query = Sale::with(['customer','invoice','items.product'])

        // 🔍 Universal search
        ->when($this->search, function ($q) {
            $q->where(function ($query) {
                $query
                    ->where('paid', 'like', "%{$this->search}%")
                    ->orWhere('due', 'like', "%{$this->search}%")
                    ->orWhereDate('sale_date', $this->search)
                    ->orWhereHas('customer', fn ($c) =>
                        $c->where('name', 'like', "%{$this->search}%")
                    )
                    ->orWhereHas('invoice', fn ($i) =>
                        $i->where('invoice_number', 'like', "%{$this->search}%")
                    );
            });
        })

        // 📅 Date range
        ->when($this->fromDate, fn ($q) =>
            $q->whereDate('sale_date', '>=', $this->fromDate)
        )
        ->when($this->toDate, fn ($q) =>
            $q->whereDate('sale_date', '<=', $this->toDate)
        )

        // 💰 Payment status filter
        ->when($this->paymentStatus, function ($q) {
            match ($this->paymentStatus) {
                'paid' => $q->where('due', 0),
                'due' => $q->where('paid', 0),
                'partial' => $q->where('paid', '>', 0)->where('due', '>', 0),
            };
        })

        ->latest();

        if ($this->perPage === 'all') {
            $sales = $query->get();
        } else {
            $sales = $query->paginate((int) $this->perPage);
        }
        
        return view('livewire.sales.sale-index', [
            'sales' => $sales
        ]);
    }
}
