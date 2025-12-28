<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesChart extends Component
{
    public $type = 'weekly'; // Options: weekly, monthly, yearly
    public $labels = [];
    public $values = [];

    protected $listeners = ['refreshChart' => '$refresh'];

    public function mount()
    {
        $this->loadChartData();
    }

    public function updatedType()
    {
        $this->loadChartData();
    }

    public function loadChartData()
    {
        switch ($this->type) {
            case 'monthly':
                $this->loadMonthlyData();
                break;
            case 'yearly':
                $this->loadYearlyData();
                break;
            case 'weekly':
            default:
                $this->loadWeeklyData();
                break;
        }

        $this->dispatch('chartDataUpdated',
            labels: $this->labels,
            values: $this->values
        );
    }

    private function loadWeeklyData()
    {
        $start = Carbon::now()->startOfWeek();
        $end = Carbon::now()->endOfWeek();
        
        // Get all days of week
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        
        // Query sales data
        $sales = DB::table('sales')
            ->selectRaw('DAYNAME(sale_date) as day, COALESCE(SUM(grand_total), 0) as total')
            ->whereBetween('sale_date', [$start, $end])
            ->groupBy('day')
            ->get()
            ->keyBy('day');
        
        $this->labels = [];
        $this->values = [];
        
        // Ensure all days are represented
        foreach ($daysOfWeek as $day) {
            $this->labels[] = $day;
            $this->values[] = isset($sales[$day]) ? (float)$sales[$day]->total : 0;
        }
    }

    private function loadMonthlyData()
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $daysInMonth = $end->day;
        
        // Query sales data
        $sales = DB::table('sales')
            ->selectRaw('DAY(sale_date) as day, COALESCE(SUM(grand_total), 0) as total')
            ->whereBetween('sale_date', [$start, $end])
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');
        
        $this->labels = [];
        $this->values = [];
        
        // Ensure all days in month are represented
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $this->labels[] = $day;
            $this->values[] = isset($sales[$day]) ? (float)$sales[$day]->total : 0;
        }
    }

    private function loadYearlyData()
    {
        $start = Carbon::now()->startOfYear();
        $end = Carbon::now()->endOfYear();
        
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        
        // Query sales data
        $sales = DB::table('sales')
            ->selectRaw('MONTH(sale_date) as month, MONTHNAME(sale_date) as month_name, COALESCE(SUM(grand_total), 0) as total')
            ->whereBetween('sale_date', [$start, $end])
            ->groupBy('month', 'month_name')
            ->orderBy('month')
            ->get()
            ->keyBy('month');
        
        $this->labels = [];
        $this->values = [];
        
        // Ensure all months are represented
        for ($month = 1; $month <= 12; $month++) {
            $this->labels[] = $months[$month - 1];
            $this->values[] = isset($sales[$month]) ? (float)$sales[$month]->total : 0;
        }
    }

    public function render()
    {
        
        return view('livewire.dashboard.sales-chart');
    }
}