<div class="container mt-3">

    <h2>Update Supplier</h2>

    <form wire:submit.prevent="update">

        <div class="mb-3">
            <label>Name</label>
            <input type="text" wire:model="name" class="form-control">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" wire:model="email" class="form-control">
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" wire:model="phone" class="form-control">
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea wire:model="address" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Details</label>
            <textarea wire:model="details" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select wire:model="status" class="form-select">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <button class="btn btn-success">Update</button>
    </form>

</div>
