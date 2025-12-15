<div class="container mt-3">

    <h2>Stock Transactions</h2>

    <div class="mb-3">
        <input type="text" wire:model="search" placeholder="Search Product..." class="form-control w-50">
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Qty Change</th>
            <th>Type</th>
            <th>Reference</th>
            <th>User</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transactions as $t)
            <tr>
                <td>{{ $t->id }}</td>
                <td>{{ $t->product->name }}</td>
                <td>{{ $t->qty_change }}</td>
                <td>{{ ucfirst($t->type) }}</td>
                <td>{{ $t->reference_id ?? '-' }}</td>
                <td>{{ $t->user->name }}</td>
                <td>{{ $t->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $transactions->links() }}
</div>
