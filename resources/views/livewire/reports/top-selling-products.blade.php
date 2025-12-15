<div>
    <div class="row mb-3">
        <div class="col-md-3">
            <label>Start Date</label>
            <input type="date" wire:model="start_date" wire:change="loadReport" class="form-control">
        </div>

        <div class="col-md-3">
            <label>End Date</label>
            <input type="date" wire:model="end_date" wire:change="loadReport" class="form-control">
        </div>
    </div>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>Product</th>
                <th>Total Qty Sold</th>
                <th>Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($results as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->total_qty }}</td>
                    <td>৳ {{ number_format($item->total_revenue, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No Data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
