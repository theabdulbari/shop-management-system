<div class="container mt-3">
    <h2>Edit Purchase</h2>

    <form wire:submit.prevent="save">

        <div class="mb-3">
            <label>Supplier</label>
            <select wire:model="supplier_id" class="form-select">
                <option value="">Select Supplier</option>
                @foreach($allSuppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
            @error('supplier_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Purchase Date</label>
            <input type="date" wire:model="purchase_date" class="form-control">
        </div>

        
        <h4>Products</h4>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th width="150">Expiry Date</th>
                    <th width="100">Qty</th>
                    <th width="120">Weight</th>
                    <th>W.unit</th>
                    <th width="120">Unit Price</th>
                    <th width="120">Subtotal</th>
                    <th><button type="button" wire:click="addProductRow" class="btn btn-success btn-sm">+</button></th>
                </tr>
            </thead>

            <tbody>
                @foreach($products as $index => $p)
                <tr wire:key="product-{{ $index }}">
                    <td>
                        <select wire:model="products.{{ $index }}.product_id" class="form-select">
                            <option value="">Select Product</option>
                            @foreach($allProducts as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                            @endforeach
                        </select>
                        @error('products.'.$index.'.product_id') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </td>

                    <td>
                        <input type="date" class="form-control" wire:model="products.{{ $index }}.expiry_date">
                    </td>

                    <td>
                        <input type="number"  wire:model.lazy="products.{{ $index }}.quantity" wire:change="calculateTotal" step="0.01" min="0" class="form-control">
                    </td>
                    <td>
                            <input type="number" wire:model="products.{{ $index }}.weight" step="0.01" min="0" class="form-control">
                    </td>
                    <td>
                        <select wire:model="products.{{ $index }}.weight_unit" class="form-select">
                            <option value="gm">gm</option>
                            <option value="ml">ml</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" wire:model.lazy="products.{{ $index }}.unit_price" wire:change="calculateTotal" step="0.01" min="0" class="form-control">
                    </td>

                    <td>{{ $p['subtotal'] }}</td>

                    <td>
                        <button type="button" wire:click="removeProductRow({{ $index }})" class="btn btn-danger btn-sm">x</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h4>Total Amount: {{ $total_amount }}</h4>

        <button class="btn btn-primary">Update Purchase</button>

    </form>
</div>
