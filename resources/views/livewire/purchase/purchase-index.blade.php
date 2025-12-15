<div class="container mt-3">
    <div class="d-flex justify-content-between mb-3">
        <h2>Purchases</h2>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary">Add Purchase</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Supplier</th>
            <th>Purchase Date</th>
            <th>Total Amount</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach($purchases as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->supplier->name }}</td>
                <td>{{ $p->purchase_date }}</td>
                <td>{{ $p->total_amount }}</td>
                <td>
                    <a href="{{ route('purchases.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <button wire:click="confirmDelete({{ $p->id }})" class="btn btn-danger btn-sm"
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                        Delete
                    </button>
                </td>
            </tr>
            @if($p->items->count() > 0)
                @foreach($p->items as $item)
                    <tr class="table-secondary">
                        <td colspan="5">-- Product: {{ $item->product->name }} | Quantity: {{ $item->quantity }} | Weight: {{ formatWeight($item->weight, $item->weight_unit) }} | Unit Price: {{ $item->unit_price }} | Subtotal: {{ $item->subtotal }} --</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
        </tbody>
    </table>

    {{ $purchases->links() }}

    <!-- Delete modal -->
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
