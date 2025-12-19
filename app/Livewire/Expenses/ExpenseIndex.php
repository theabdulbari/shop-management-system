<?php

namespace App\Livewire\Expenses;
use App\Livewire\Traits\WithPerPagePagination;
use App\Livewire\Traits\WithUniversalSearch;

use Livewire\Component;
use App\Models\Expense;

class ExpenseIndex extends Component
{
    public $perPage = 25;
    public function render()
    {
        $query = Expense::with('category','user')
                ->latest();

        $expenses = $this->perPage === 'all'
            ? $query->get()
            : $query->paginate($this->perPage);
        return view('livewire.expenses.expense-index', [
            'expenses' => $expenses]);
    }
}

