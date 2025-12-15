<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use App\Models\Customer;

class CustomerCreate extends Component
{
    public $name, $email, $phone, $address, $status = 'active';

    public function save()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'required',
        ]);

        Customer::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Customer added successfully.');
        return redirect()->route('customers.index');
    }

    public function render()
    {
        return view('livewire.customers.customer-create');
    }
}
