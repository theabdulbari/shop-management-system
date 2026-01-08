<div class="container mt-3">
    <div class="d-flex justify-content-between mb-3">
        <h2>Sales</h2>
        <a href="{{ route('sales.create') }}" class="btn btn-primary">Add Sale</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

        <div class="row mb-3 align-items-end">
            {{-- Search --}}
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text"
                    class="form-control"
                    placeholder="Customer, Invoice, Paid, Due..."
                    wire:model.lazy.debounce.500ms="search">
            </div>

            {{-- Payment Status --}}
            <div class="col-md-2">
                <label class="form-label">Payment Status</label>
                <select class="form-select" wire:model.lazy="paymentStatus">
                    <option value="">All</option>
                    <option value="paid">Paid</option>
                    <option value="due">Due</option>
                    <option value="partial">Partial</option>
                </select>
            </div>

            {{-- From Date --}}
            <div class="col-md-2">
                <label class="form-label">From</label>
                <input type="date" class="form-control" wire:model.lazy="fromDate">
            </div>

            {{-- To Date --}}
            <div class="col-md-2">
                <label class="form-label">To</label>
                <input type="date" class="form-control" wire:model.lazy="toDate">
            </div>

            {{-- Per Page --}}
            <div class="col-md-2">
                <label class="form-label">Per Page</label>
                <select class="form-select" wire:model.lazy="perPage">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="all">All</option>
                </select>
            </div>

            {{-- Reset --}}
            <div class="col-md-1">
                <button class="btn btn-secondary w-100"
                        wire:click="resetFilters">
                    Reset
                </button>
            </div>
        </div>



    <table class="table table-bordered">
        <thead>
        <tr>
            <th width="40"></th>
            <th>#Invoice</th>
            <th>Customer</th>
            <th>Sale Date</th>
            {{-- <th>User</th> --}}
            <th>Grand Total</th>
            <th>Paid</th>
            <th>Due</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sales as $s)
            <tr>
                <td class="text-center">
                    <button
                        wire:click="toggleItems({{ $s->id }})"
                        class="btn btn-sm btn-outline-secondary">
                        {{ $expandedSaleId === $s->id ? '−' : '+' }}
                    </button>
                </td>
                <td>{{ $s->invoice->invoice_number }}</td>
                <td>{{ $s->customer?->name ?? 'Walk-in' }}</td>
                <td>{{ $s->sale_date }}</td>
                {{-- <td>{{ $s->user->name }}</td> --}}
                
                <td>{{ money($s->grand_total) }}</td>
                <td>{{ money($s->paid) }}</td>
                <td>{{ money($s->due) }}</td>
                <td>
                    <button wire:click="confirmDelete({{ $s->id }})" class="btn btn-danger btn-sm"
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                        Delete
                    </button>
                    <a class="btn btn-warning btn-sm" href="{{ route('sales.edit', $s->id) }}">Edit</a>
                </td>
            </tr>

            {{-- Sale Items Row (Hidden / Shown) --}}
            @if($expandedSaleId === $s->id)
                <tr class="bg-light">
                    <td colspan="8">
                        <table class="table table-sm table-bordered mb-0">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($s->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ money($item->unit_price) }}</td>
                                    <td>{{ money($item->qty * $item->unit_price) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endif

        @endforeach
        </tbody>
    </table>
    <div class="card-footer d-flex justify-content-center">
        @if($perPage !== 'all')
            {{ $sales->links('pagination::bootstrap-5') }}
        @endif
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Are you sure?</h4>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button wire:click="delete" class="btn btn-danger" data-bs-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
