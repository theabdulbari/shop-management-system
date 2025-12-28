<div class="container mt-3">

    <h2>Inventory / Stock</h2>

    <div class="mb-3 d-flex justify-content-between">
        <input type="text" wire:model="search" placeholder="Search Product..." class="form-control w-50">
        <a href="{{ route('stock.transactions') }}" class="btn btn-primary">View Stock Transactions</a>
    </div>

    <div class="mb-3">
        <span>Total Stock Value: {{ $totalStockValue }}</span> |
        <span>Low Stock Count: {{ $lowStockCount }}</span>
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>SKU</th>
            <th>Product</th>
            <th>Category</th>
            <th>Stock Qty</th>
            <th>Unit</th>
            <th>Cost Price</th>
            <th>Sell Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $p)
            <tr @if($p->stock_qty <= $p->stock_alert_qty) class="table-danger" @endif>
                <td>{{ $p->sku }}</td>
                <td>{{ $p->name }}</td>
                <td>{{ $p->category?->name }}</td>
                <td>{{ $p->stock_qty }}</td>
                <td>{{ $p->unit }}</td>
                <td>{{ $p->purchase_price }}</td>
                <td>{{ $p->sell_price }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="card-footer d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
