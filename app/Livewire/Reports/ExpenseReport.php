<?php
namespace App\Livewire\Expenses;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Expense;

class ExpenseReport extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $from_date;
    public $to_date;

    public function render()
    {
        $query = Expense::with('category','user')->latest();

        if ($this->from_date) {
            $query->whereDate('expense_date', '>=', $this->from_date);
        }

        if ($this->to_date) {
            $query->whereDate('expense_date', '<=', $this->to_date);
        }

        return view('livewire.expenses.expense-report', [
            'expenses' => $query->paginate(10),
            'total' => $query->sum('amount')
        ]);
    }
}
