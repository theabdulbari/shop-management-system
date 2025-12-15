<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;

class Edit extends Component
{
    public $user;
    public $name, $email, $role;

    public function mount($id)
    {
        $this->user  = User::findOrFail($id);
        $this->name  = $this->user->name;
        $this->email = $this->user->email;

        $this->role = $this->user->roles->pluck('name')->first();
    }

    public function update()
    {
        $this->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'role'  => 'required'
        ]);

        $this->user->update([
            'name'  => $this->name,
            'email' => $this->email,
        ]);

        $this->user->syncRoles([$this->role]);

        session()->flash('success', 'User updated successfully.');

        return redirect()->route('users.index');
    }

    public function render()
    {
        $roles = Role::all();
        return view('livewire.users.edit', compact('roles'));
    }
}
