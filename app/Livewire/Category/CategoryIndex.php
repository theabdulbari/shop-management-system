<?php

namespace App\Livewire\Category;

use App\Livewire\Traits\WithPerPagePagination;
use App\Livewire\Traits\WithUniversalSearch;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class CategoryIndex extends Component
{
    use WithPagination;

    public $deleteId;

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        Category::find($this->deleteId)->delete();
        session()->flash('success', 'Category deleted successfully');
    }

    public function render()
    {

        $query = Category::latest();     
        $categories = $this->perPage === 'all'
            ? $query->get()
            : $query->paginate($this->perPage);
        return view('livewire.category.category-index', [
            'categories' => $categories
        ]);
    }
}
