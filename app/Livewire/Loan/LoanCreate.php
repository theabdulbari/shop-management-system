<?php

// app/Livewire/Loan/LoanCreate.php

namespace App\Livewire\Loan;

use Livewire\Component;
use App\Models\Loan;

class LoanCreate extends Component
{
    public $payer_name, $phone, $address;
    public $loan_date, $possible_paid_date;
    public $amount, $paid = 0, $note;

    protected $rules = [
        'payer_name' => 'required',
        'loan_date' => 'required|date',
        'amount' => 'required|numeric|min:0.01',
        'paid' => 'nullable|numeric|min:0',
    ];

    public function save()
    {
        $this->validate();

        $loan = Loan::create([
            'payer_name' => $this->payer_name,
            'phone' => $this->phone,
            'address' => $this->address,
            'loan_date' => $this->loan_date,
            'possible_paid_date' => $this->possible_paid_date,
            'amount' => $this->amount,
            'paid' => $this->paid ?? 0,
            'due' => $this->amount,
            'note' => $this->note,
        ]);

        $loan->recalculate();

        session()->flash('success','Loan created');
        return redirect()->route('loans.index');
    }

    public function render()
    {
        return view('livewire.loan.loan-create');
    }
}
