<div class="container mt-4">

    <h3>Add New User</h3>

    <form wire:submit.prevent="save" class="mt-3">

        <div class="mb-3">
            <label>Name</label>
            <input type="text" class="form-control" wire:model="name">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" wire:model="email">
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" class="form-control" wire:model="password">
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select class="form-control" wire:model="role">
                <option value="">Select</option>
                @foreach($roles as $r)
                    <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Save</button>
    </form>

</div>
