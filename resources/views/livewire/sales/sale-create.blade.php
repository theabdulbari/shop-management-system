<div class="container mt-3">
    <h2>Add Sale</h2>


    <form wire:submit.prevent="save">

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
                        <td wire:key="product-search-{{ $index }}" class="product-search-wrapper" onclick="event.stopPropagation()"  style="position: relative">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Search product..."
                                wire:model.live.debounce.300ms="search.{{ $index }}"
                                wire:focus="$set('showDropdown.{{ $index }}', true)"
                            >

                            @if($showDropdown[$index] ?? false)
                                <ul
                                    wire:ignore.self
                                    class="list-group position-absolute w-100 shadow"
                                    style="z-index:999; max-height:220px; overflow-y:auto;"
                                >
                                    @php
                                        $filtered = $allProducts->filter(fn ($p) =>
                                            empty($search[$index]) ||
                                            str_contains(
                                                strtolower($p->name),
                                                strtolower($search[$index])
                                            )
                                        );
                                    @endphp

                                    @forelse($filtered as $prod)
                                        <li
                                            class="list-group-item list-group-item-action"
                                            style="cursor:pointer"
                                            wire:mousedown.prevent="selectProduct({{ $index }}, {{ $prod->id }})"
                                        >
                                            {{ $prod->name }}
                                            <small class="text-muted">
                                                (Stock: {{ $prod->stock_qty }})
                                            </small>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-muted">
                                            No product found
                                        </li>
                                    @endforelse
                                </ul>
                            @endif
                        </td>
                        <td><input type="number" wire:model.lazy="products.{{ $index }}.qty" step="0.001" min="0" class="form-control"></td>
                        <td><input type="number" wire:model.lazy="products.{{ $index }}.unit_price" step="0.001" min="0" class="form-control"></td>
                        <td>{{ $p['subtotal'] ?? 0 }}</td>
                        <td><button type="button" wire:click="removeProductRow({{ $index }})" class="btn btn-danger btn-sm">x</button></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>
                        <label>Discount:</label>
                        <input type="number" wire:model.live="discount" step="0.001" min="0" value="0" class="form-control">
                    </td>
                    <td>
                        <!-- #region -->
                        <label>Shipping:</label>
                        <input type="number" name="shipping" value="0" class="form-control">
                    </td>
                    <td>
                        <!-- #region -->
                        <label>Paid:</label>
                        <input type="number" wire:model.live="paid" step="0.001" min="0"  value="0" class="form-control">
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
                    <h4>Discount: {{ money(@$discount) }}</h4>
                </td>
                <td>
                    <h4>Grand Total: {{ money($grand_total) }}</h4>
                </td>
                <td>
                    <h4>Due: {{ money(@$due) }}</h4>
                </td>
                <td>
                    <h4>Paid: {{ money(@$paid) }}</h4>
                </td>
                <td>
                    <h4>Status: {{ ucfirst($payment_status) }}</h4>
                </td>
            </tr>

        </table>

        <button class="btn btn-success">Save Sale</button>
    </form>
</div>


{{-- <td><input type="number" wire:model="products.{{ $index }}.tax" class="form-control"></td> --}}

<script>
// document.addEventListener('click', function (e) {
//     if (!e.target.closest('.product-search-wrapper')) {
//         // Livewire.dispatch('closeAllProductDropdowns');
//     }
// });
document.addEventListener('click', function () {
    Livewire.dispatch('closeAllProductDropdowns');
});
</script>
