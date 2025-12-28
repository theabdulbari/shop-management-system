<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Sale;

class CustomerDueList extends Component
{
    public $expandedCustomer = null;

    public function toggle($customerId)
    {
        $this->expandedCustomer =
            $this->expandedCustomer === $customerId ? null : $customerId;
    }

    public function render()
    {
        $customers = Customer::whereHas('sales', function ($q) {
                $q->where('due', '>', 0);
            })
            ->withSum(['sales as total_due' => function ($q) {
                $q->where('due', '>', 0);
            }], 'due')
            ->with(['sales' => function ($q) {
                $q->where('due', '>', 0)
                  ->with('invoice:id,invoice_number,invoice_date')
                  ->orderBy('invoice_id');
            }])
            ->get();

            $totalDue = Sale::where('due', '>', 0)->sum('due');

        return view('livewire.dashboard.customer-due-list', compact('customers', 'totalDue'));
    }
}
