<?php

namespace App\Livewire\Customers;

use App\Livewire\Traits\WithPerPagePagination;
use App\Livewire\Traits\WithUniversalSearch;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;

class CustomerIndex extends Component
{
    use WithPagination;
    public $perPage = 25;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteCustomer($id)
    {
        Customer::find($id)->delete();
        session()->flash('success', 'Customer deleted successfully.');
    }

    public function render()
    {
        $query = Customer::where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%')
                             ->orderBy('id', 'desc');
        $customers = $this->perPage === 'all'
            ? $query->get()
            : $query->paginate($this->perPage);

        return view('livewire.customers.customer-index', compact('customers'));
    }
}
