<div class="container mt-3">

    <h2>Add Category</h2>

    <form wire:submit.prevent="save">

        <div class="mb-3">
            <label>Name</label>
            <input type="text" wire:model="name" class="form-control">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea wire:model="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select wire:model="status" class="form-select">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <button class="btn btn-success">Save</button>
    </form>
</div>
