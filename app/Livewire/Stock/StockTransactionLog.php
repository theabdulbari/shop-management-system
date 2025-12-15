<?php

namespace App\Livewire\Stock;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StockTransaction;

class StockTransactionLog extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $query = StockTransaction::with('product','user')->latest();

        if($this->search){
            $query->whereHas('product', function($q){
                $q->where('name','like','%'.$this->search.'%');
            });
        }

        $transactions = $query->paginate(15);

        return view('livewire.stock.stock-transaction-log', [
            'transactions' => $transactions
        ]);
    }
}
