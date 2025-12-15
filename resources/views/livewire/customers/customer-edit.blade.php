<div class="container mt-3">
    <h2>Edit Customer</h2>

    @if(session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="update">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Name</label>
                <input type="text" wire:model.lazy="name" class="form-control">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label>Email</label>
                <input type="email" wire:model.lazy="email" class="form-control">
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label>Phone</label>
                <input type="text" wire:model.lazy="phone" class="form-control">
            </div>

            <div class="col-md-12 mb-3">
                <label>Address</label>
                <textarea wire:model.lazy="address" class="form-control"></textarea>
            </div>

            <div class="col-md-4 mb-3">
                <label>Status</label>
                <select wire:model.lazy="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
