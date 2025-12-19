<?php

namespace App\Livewire\Products;

use App\Livewire\Traits\WithPerPagePagination;
use App\Livewire\Traits\WithUniversalSearch;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class ProductIndex extends Component
{
    use WithPagination;
    public $perPage = 25;
    public $search = '';
    // public $perPage = 10; 
    public $deleteId;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        Product::find($this->deleteId)->delete();
        session()->flash('success', 'Product deleted successfully!');
    }

    public function render()
    {
        $query = Product::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest();

        $products = $this->perPage === 'all'
            ? $query->get()
            : $query->paginate($this->perPage);

        return view('livewire.products.product-index', compact('products'));
    }
}
