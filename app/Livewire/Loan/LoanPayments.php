<?php

namespace App\Livewire\Loan;

use Livewire\Component;
use App\Models\Loan;

// app/Livewire/Loan/LoanPayments.php

class LoanPayments extends Component
{
    public Loan $loan;
    public $payments = [];

    public function mount(Loan $loan)
    {
        $this->loan = $loan->load('payments');
    }

    public function render()
    {
        return view('livewire.loan.loan-payments');
    }
}

