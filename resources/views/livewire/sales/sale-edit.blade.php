<div class="container mt-3">
<h2>Edit Sale</h2>

    <form wire:submit.prevent="update">

        <div class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label>Customer</label>
                    <select wire:model.lazy="customer_id" class="form-select">
                        {{-- <option value="">Walk-in Customer</option> --}}
                        @foreach($allCustomers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Date</label>
                    <input type="date" wire:model="date" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Invoice Number</label>
                    <input type="text" wire:model="invoice_number" class="form-control" readonly>
                </div>
            </div>
            
        </div>

        <h4>Products</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                    <th><button type="button" wire:click="addProductRow" class="btn btn-success btn-sm">+</button></th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $index => $p)
                    <tr>
                        <td>
                            <select wire:model="products.{{ $index }}.product_id" wire:change="loadProductPrice({{ $index }})" class="form-select">
                                <option value="">Select Product</option>
                                @foreach($allProducts as $prod)
                                    <option value="{{ $prod->id }}">{{ $prod->name }} (Stock: {{ $prod->stock_qty }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" wire:model.lazy="products.{{ $index }}.qty" step="0.01" min="0" class="form-control"></td>
                        <td><input type="number" wire:model.lazy="products.{{ $index }}.unit_price" step="0.01" min="0" class="form-control"></td>
                        <td>{{ $p['subtotal'] ?? 0 }}</td>
                        <td><button type="button" wire:click="removeProductRow({{ $index }})" class="btn btn-danger btn-sm">x</button></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>
                        <label>Discount:</label>
                        <input type="number" wire:model.live="discount" value="0" class="form-control">
                    </td>
                    <td>
                        <!-- #region -->
                        <label>Shipping:</label>
                        <input type="number" name="shipping" value="0" class="form-control">
                    </td>
                    <td>
                        <!-- #region -->
                        <label>Paid:</label>
                        <input type="number" wire:model.live="paid"  value="0" class="form-control">
                    </td>
                    <td>
                        <label>Payment Status:</label>
                        <select wire:model="payment_status" class="form-select">
                            <option value="paid">Paid</option>
                            <option value="partial">Partial</option>
                            <option value="due">Due</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tfoot>
        </table>

        <table  class="table table-bordered">
            <tr>
                <td>
                    <h4>Discount: {{ @$discount }}</h4>
                </td>
                <td>
                    <h4>Grand Total: {{ $grand_total }}</h4>
                </td>
                <td>
                    <h4>Due: {{ @$due }}</h4>
                </td>
                <td>
                    <h4>Paid: {{ @$paid }}</h4>
                </td>
                <td>
                    <h4>Status: {{ ucfirst($payment_status) }}</h4>
                </td>
            </tr>

        </table>
        
        
        
        
        

        <button class="btn btn-success">Update Sale</button>
    </form>
</div>


{{-- <td><input type="number" wire:model="products.{{ $index }}.tax" class="form-control"></td> --}}