<div class="card">
    <div class="card-header bg-danger text-white">
        <strong>Low Stock Alert</strong>
    </div>

    <ul class="list-group list-group-flush">
        @forelse ($products as $product)
            <li class="list-group-item d-flex justify-content-between">
                <span>{{ $product->name }}</span>
                <span class="badge bg-danger">
                    {{ $product->stock_qty }} / {{ $product->stock_alert_qty }}
                </span>
            </li>
        @empty
            <li class="list-group-item">All stocks are healthy 🎉</li>
        @endforelse
    </ul>
</div>
