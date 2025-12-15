<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryEdit extends Component
{
    public $category;
    public $name, $description, $status;

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->status = $category->status;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|unique:categories,name,' . $this->category->id,
            'description' => 'nullable',
            'status' => 'required'
        ]);

        $this->category->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Category updated successfully');
        return redirect()->route('categories.index');
    }

    public function render()
    {
        return view('livewire.category.category-edit');
    }
}
