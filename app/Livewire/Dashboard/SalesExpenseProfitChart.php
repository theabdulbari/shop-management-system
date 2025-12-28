<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Sale;
use App\Models\Expense;
use Carbon\Carbon;

class SalesExpenseProfitChart extends Component
{
    public string $range = 'weekly';

    public function mount()
    {
        $this->loadChart();
    }

    public function updatedRange()
    {
        $this->loadChart();
    }

    private function loadChart()
    {
        [$labels, $sales, $expenses, $profits] = match ($this->range) {
            'monthly' => $this->monthly(),
            'yearly'  => $this->yearly(),
            default   => $this->weekly(),
        };

        // ✅ Livewire 3 event dispatch
        $this->dispatch('chart-update', [
            'labels'   => $labels,
            'sales'    => $sales,
            'expenses' => $expenses,
            'profits'  => $profits,
        ]);
    }

    private function weekly()
    {
        $labels = [];
        $sales = [];
        $expenses = [];
        $profits = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            $sale = Sale::whereDate('sale_date', $date)->sum('total_amount');
            $expense = Expense::whereDate('expense_date', $date)->sum('amount');

            $labels[] = $date->format('D');
            $sales[] = $sale;
            $expenses[] = $expense;
            $profits[] = $sale - $expense;
        }

        return [$labels, $sales, $expenses, $profits];
    }

    private function monthly()
    {
        $labels = [];
        $sales = [];
        $expenses = [];
        $profits = [];

        for ($i = 1; $i <= 12; $i++) {
            $sale = Sale::whereMonth('sale_date', $i)->sum('total_amount');
            $expense = Expense::whereMonth('expense_date', $i)->sum('amount');

            $labels[] = Carbon::create()->month($i)->format('M');
            $sales[] = $sale;
            $expenses[] = $expense;
            $profits[] = $sale - $expense;
        }

        return [$labels, $sales, $expenses, $profits];
    }

    private function yearly()
    {
        $labels = [];
        $sales = [];
        $expenses = [];
        $profits = [];

        $years = range(now()->year - 4, now()->year);

        foreach ($years as $year) {
            $sale = Sale::whereYear('sale_date', $year)->sum('total_amount');
            $expense = Expense::whereYear('expense_date', $year)->sum('amount');

            $labels[] = (string) $year;
            $sales[] = $sale;
            $expenses[] = $expense;
            $profits[] = $sale - $expense;
        }

        return [$labels, $sales, $expenses, $profits];
    }

    public function render()
    {
        return view('livewire.dashboard.sales-expense-profit-chart');
    }
}
