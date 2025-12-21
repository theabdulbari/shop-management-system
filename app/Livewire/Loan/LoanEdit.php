<?php
// app/Livewire/Loan/LoanEdit.php

namespace App\Livewire\Loan;

use Livewire\Component;
use App\Models\Loan;

class LoanEdit extends Component
{
    public Loan $loan;
    public array $form = [];

    protected $rules = [
        'form.payer_name' => 'required|string',
        'form.phone' => 'nullable|string',
        'form.address' => 'nullable|string',
        'form.loan_date' => 'required|date',
        'form.possible_paid_date' => 'nullable|date',
        'form.amount' => 'required|numeric|min:0.01',
        'form.paid' => 'nullable|numeric|min:0',
        'form.note' => 'nullable|string',
    ];

    public function mount(Loan $loan)
    {
        $this->loan = $loan;
        $this->form = $this->loan->toArray();

        // Ensure dates are in 'Y-m-d' format for <input type="date">
        if ($this->form['loan_date']) {
            $this->form['loan_date'] = date('Y-m-d', strtotime($this->form['loan_date']));
        }
        if ($this->form['possible_paid_date']) {
            $this->form['possible_paid_date'] = date('Y-m-d', strtotime($this->form['possible_paid_date']));
        }
    }

    public function update()
    {
        $this->validate();

        $this->loan->update($this->form);

        // Optional: if your Loan model has a recalculate method
        if (method_exists($this->loan, 'recalculate')) {
            $this->loan->recalculate();
        }

        session()->flash('success', 'Loan updated successfully');
        return redirect()->route('loans.index');
    }

    public function render()
    {
        return view('livewire.loan.loan-edit');
    }
}
