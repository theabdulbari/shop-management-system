<?php

namespace App\Livewire\Expenses;

use Livewire\Component;
use App\Models\Expense;
use App\Models\ExpenseCategory;

class ExpenseEdit extends Component
{
    public Expense $expense;

    public function mount(Expense $expense)
    {
        $this->expense = $expense;
    }

    public function update()
    {
        $this->validate([
            'expense.title' => 'required',
            'expense.expense_category_id' => 'required',
            'expense.expense_date' => 'required',
            'expense.amount' => 'required|numeric|min:1',
        ]);

        $this->expense->save();

        session()->flash('success', 'Expense updated successfully');
        return redirect()->route('expenses.index');
    }

    public function render()
    {
        return view('livewire.expenses.expense-edit', [
            'categories' => ExpenseCategory::where('status',1)->get()
        ]);
    }
}

