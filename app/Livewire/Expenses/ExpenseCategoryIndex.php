<?php

namespace App\Livewire\Expenses;

use Livewire\Component;
use App\Models\ExpenseCategory;

class ExpenseCategoryIndex extends Component
{
    public $name, $note, $status = 1, $category_id;

    protected $rules = [
        'name' => 'required|string|max:255'
    ];

    public function save()
    {
        $this->validate();

        ExpenseCategory::updateOrCreate(
            ['id' => $this->category_id],
            [
                'name' => $this->name,
                'note' => $this->note,
                'status' => $this->status
            ]
        );

        $this->reset();
        session()->flash('success','Category saved');
    }

    public function edit($id)
    {
        $cat = ExpenseCategory::findOrFail($id);
        $this->category_id = $cat->id;
        $this->name = $cat->name;
        $this->note = $cat->note;
        $this->status = $cat->status;
    }

    public function delete($id)
    {
        ExpenseCategory::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.expenses.expense-category-index', [
            'categories' => ExpenseCategory::latest()->get()
        ]);
    }
}

