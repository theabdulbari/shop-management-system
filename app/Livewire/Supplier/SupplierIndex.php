<?php

namespace App\Livewire\Supplier;

use App\Livewire\Traits\WithPerPagePagination;
use App\Livewire\Traits\WithUniversalSearch;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Supplier;

class SupplierIndex extends Component
{
    use WithPagination;

    public $perPage = 25;
    public $search = '';
    public $deleteId;

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        Supplier::find($this->deleteId)->delete();
        session()->flash('success', 'Supplier deleted successfully.');
    }

    public function render()
    {
        $query = Supplier::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest();

        $suppliers = $this->perPage === 'all'
            ? $query->get()
            : $query->paginate($this->perPage);

        return view('livewire.supplier.supplier-index', [
            'suppliers' => $suppliers
        ]);
    }
}
