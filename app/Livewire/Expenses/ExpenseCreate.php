<?php

namespace App\Livewire\Expenses;

use Livewire\Component;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\uses;

class ExpenseCreate extends Component
{
    public $title, $expense_category_id, $expense_date, $amount;
    public $payment_method = 'cash', $reference, $note;
    public $products = [];

    public function mount()
    {
        $this->products = Product::where('status', 1)->get();
    }

    public function save()
    {
        $this->validate([
            'title' => 'required',
            'expense_category_id' => 'required',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
        ]);

        Expense::create([
            'title' => $this->title,
            'expense_category_id' => $this->expense_category_id,
            'expense_date' => $this->expense_date,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'reference' => $this->reference,
            'note' => $this->note,
            'user_id' => Auth::id(),
        ]);

        session()->flash('success', 'Expense added successfully');
        return redirect()->route('expenses.index');
    }

    public function render()
    {
        return view('livewire.expenses.expense-create', [
            'categories' => ExpenseCategory::where('status',1)->get()
        ]);
    }
}
