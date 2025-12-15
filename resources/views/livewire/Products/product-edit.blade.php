<div class="container mt-3">
    <h2>Edit Product</h2>

    @if(session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save">

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Category</label>
                <select wire:model.lazy="category_id" class="form-select">
                    <option value="">Select Category</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label>Supplier</label>
                <select wire:model.lazy="supplier_id" class="form-select">
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label>Name</label>
                <input type="text" wire:model.lazy="name" class="form-control">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-3">
                <label>SKU Preview</label>
                <input type="text" class="form-control" value="{{ $skuPreview }}" disabled>
            </div>

            <div class="col-md-3 mb-3">
                <label>Unit</label>
                <input type="text" wire:model="unit" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>Purchase Price</label>
                <input type="number" step="0.01" min="0" wire:model="purchase_price" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label>Barcode Preview</label>
                <input type="text" class="form-control" value="{{ $barcodePreview }}" disabled>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Sell Price</label>
                <input type="number" step="0.01" min="0" wire:model="sell_price" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Stock</label>
                <input type="number"  step="0.01" min="0" wire:model="stock_qty" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Status</label>
                <select wire:model="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        <button class="btn btn-success">Update</button>
    </form>
</div>
