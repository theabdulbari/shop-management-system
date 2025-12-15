<?php
namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Expense;

class ProfitLossReport extends Component
{
    public $from_date;
    public $to_date;

    public function getReport()
    {
        $sales = Sale::whereBetween('created_at', [$this->from_date, $this->to_date])
            ->sum('grand_total');

        $purchase = Purchase::whereBetween('purchase_date', [$this->from_date, $this->to_date])
            ->sum('grand_total');

        $expense = Expense::whereBetween('expense_date', [$this->from_date, $this->to_date])
            ->sum('amount');

        return [
            'sales' => $sales,
            'purchase' => $purchase,
            'expense' => $expense,
            'profit' => $sales - $purchase - $expense
        ];
    }

    public function render()
    {
        return view('livewire.reports.profit-loss-report', [
            'data' => $this->from_date && $this->to_date ? $this->getReport() : null
        ]);
    }
}
