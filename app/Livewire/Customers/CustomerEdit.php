<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use App\Models\Customer;

class CustomerEdit extends Component
{
    public Customer $customer;

    public $name, $email, $phone, $address, $status;

    public function mount(Customer $customer)
    {
        $this->customer = $customer;

        // Initialize fields with existing data
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->address = $customer->address;
        $this->status = $customer->status;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => 'nullable|email|unique:customers,email,' . $this->customer->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'required',
        ]);

        $this->customer->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Customer updated successfully.');
        return redirect()->route('customers.index');
    }

    public function render()
    {
        return view('livewire.customers.customer-edit');
    }
}
