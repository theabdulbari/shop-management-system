<?php

// app/Livewire/Loan/LoanIndex.php

namespace App\Livewire\Loan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Loan;
use App\Models\LoanPayment;

class LoanIndex extends Component
{
    use WithPagination;
    public $perPage = 25;
    public $search = '';
    public $status = '';
    public $deleteId;

    public $selectedLoan;
    public $payments = [];
    public $paymentAmount;
    public $paymentDate;
    public $paymentNote;

    protected $queryString = ['search', 'status'];

    protected $rules = [
        'paymentAmount' => 'required|numeric|min:0.01',
        'paymentDate'   => 'required|date',
    ];

    public function openPaymentModal($loanId)
    {
        $this->selectedLoan = Loan::findOrFail($loanId);
        $this->paymentAmount = '';
        $this->paymentDate = now()->format('Y-m-d');
        $this->paymentNote = '';
    }
    public function viewPayments($loanId)
    {
        $this->selectedLoan = Loan::findOrFail($loanId);
        $this->payments = $this->selectedLoan
            ->payments()
            ->orderBy('paid_date', 'desc')
            ->get();
    }

    public function savePayment()
    {
        $this->validate();

        LoanPayment::create([
            'loan_id'   => $this->selectedLoan->id,
            'amount'    => $this->paymentAmount,
            'paid_date' => $this->paymentDate,
            'note'      => $this->paymentNote,
        ]);

        // update loan
        $this->selectedLoan->paid += $this->paymentAmount;
        $this->selectedLoan->recalculate();

        session()->flash('success', 'Payment added successfully');

        $this->dispatch('close-payment-modal');
    }
    public function updateStatus($loanId, $status)
    {
        $loan = Loan::findOrFail($loanId);

        $loan->setStatus($status);

        session()->flash('success', 'Loan status updated');
    }
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        Loan::findOrFail($this->deleteId)->delete();
        session()->flash('success', 'Loan deleted successfully');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $loans = Loan::query()
            ->when($this->search, fn ($q) =>
                $q->where('payer_name', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%")
            )
            ->when($this->status, fn ($q) =>
                $q->where('status', $this->status)
            )
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.loan.loan-index', compact('loans'));
    }
}

