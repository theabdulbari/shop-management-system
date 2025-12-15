<div class="container mt-4">

    <h3>Edit User</h3>

    <form wire:submit.prevent="update" class="mt-3">

        <div class="mb-3">
            <label>Name</label>
            <input type="text" class="form-control" wire:model="name">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" wire:model="email">
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select class="form-control" wire:model="role">
                @foreach($roles as $r)
                    <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>

</div>
