<?php

namespace App\Livewire\Users;

use App\Livewire\Traits\WithPerPagePagination;
use App\Livewire\Traits\WithUniversalSearch;

use Livewire\Component;
use App\Models\User;

class Index extends Component
{
    public $search = '';

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('success', 'User deleted successfully.');
    }

    public function render()
    {
        $users = User::with('roles')
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.users.index', compact('users'));
    }
}
