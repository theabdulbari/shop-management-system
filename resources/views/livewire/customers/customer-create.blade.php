<div class="container mt-3">
    <h2>Add Customer</h2>

    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Name</label>
                <input type="text" wire:model="name" class="form-control">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label>Email</label>
                <input type="email" wire:model="email" class="form-control">
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label>Phone</label>
                <input type="text" wire:model="phone" class="form-control">
            </div>
            <div class="col-md-12 mb-3">
                <label>Address</label>
                <textarea wire:model="address" class="form-control"></textarea>
            </div>
            <div class="col-md-4 mb-3">
                <label>Status</label>
                <select wire:model="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <button class="btn btn-success">Save</button>
    </form>
</div>
