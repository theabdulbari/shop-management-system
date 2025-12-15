<?php

namespace App\Livewire\Expenses;

use Livewire\Component;
use App\Models\Expense;
use Carbon\Carbon;

class MonthlyExpenseChart extends Component
{
    public $year;
    public $chartData = [];

    public function mount()
    {
        $this->year = now()->year;
        $this->loadChart();
    }

    public function updatedYear()
    {
        $this->loadChart();
    }

    private function loadChart()
    {
        $data = Expense::selectRaw('MONTH(expense_date) as month, SUM(amount) as total')
            ->whereYear('expense_date', $this->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        $totals = [];

        for ($m = 1; $m <= 12; $m++) {
            $months[] = Carbon::create()->month($m)->format('M');

            $record = $data->firstWhere('month', $m);
            $totals[] = $record ? (float)$record->total : 0;
        }

        $this->chartData = [
            'labels' => $months,
            'data'   => $totals,
        ];

        $this->dispatch('expense-chart-updated', $this->chartData);
    }

    public function render()
    {
        return view('livewire.expenses.monthly-expense-chart');
    }
}
