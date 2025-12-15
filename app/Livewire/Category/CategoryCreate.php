<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryCreate extends Component
{
    public $name, $description, $status = 1;

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:categories,name',
            'description' => 'nullable',
            'status' => 'required|boolean'
        ]);

        Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Category created successfully');
        return redirect()->route('categories.index');
    }

    public function render()
    {
        return view('livewire.category.category-create');
    }
}
