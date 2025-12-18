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

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        Product::find($this->deleteId)->delete();
        session()->flash('success', 'Product deleted successfully!');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
        $products = Product::with('category')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.products.product-index', compact('products'));
    }
}
