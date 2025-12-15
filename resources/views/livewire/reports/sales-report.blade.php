<div class="container mt-4">

    <h3 class="mb-4">Sales Report</h3>

    {{-- Filters --}}
    <div class="card p-3 mb-3">
        <div class="row">

            <div class="col-md-3">
                <label>Start Date</label>
                <input type="date" wire:model="start_date" class="form-control">
            </div>

            <div class="col-md-3">
                <label>End Date</label>
                <input type="date" wire:model="end_date" class="form-control">
            </div>

            <div class="col-md-6 d-flex align-items-end gap-2">
                <button wire:click="setFilter('today')" class="btn btn-sm btn-primary">Today</button>
                <button wire:click="setFilter('7days')" class="btn btn-sm btn-info">Last 7 Days</button>
                <button wire:click="setFilter('15days')" class="btn btn-sm btn-info">15 Days</button>
                <button wire:click="setFilter('1month')" class="btn btn-sm btn-success">1 Month</button>
                <button wire:click="setFilter('3months')" class="btn btn-sm btn-warning">3 Months</button>
                <button wire:click="setFilter('6months')" class="btn btn-sm btn-danger">6 Months</button>
            </div>

        </div>
    </div>

    {{-- Summary --}}
    @php
        $total_sales = $sales->sum('grand_total');
        $total_discount = $sales->sum('discount');
        $total_tax = $sales->sum('tax');
        $total_shipping = $sales->sum('shipping');
    @endphp

    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card bg-success text-white p-3">
                <h4>৳ {{ number_format($total_sales, 2) }}</h4>
                <small>Total Sales</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white p-3">
                <h4>৳ {{ number_format($total_discount, 2) }}</h4>
                <small>Total Discount</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white p-3">
                <h4>৳ {{ number_format($total_tax, 2) }}</h4>
                <small>Total Tax</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-primary text-white p-3">
                <h4>৳ {{ number_format($total_shipping, 2) }}</h4>
                <small>Total Shipping</small>
            </div>
        </div>

    </div>

    {{-- Table --}}
    <div class="card p-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice</th>
                    <th>Total</th>
                    <th>Discount</th>
                    <th>Tax</th>
                    <th>Shipping</th>
                    <th>Grand Total</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->created_at->format('d M Y') }}</td>
                    <td>{{ $sale->invoice_no }}</td>
                    <td>৳ {{ $sale->total_amount }}</td>
                    <td>৳ {{ $sale->discount }}</td>
                    <td>৳ {{ $sale->tax }}</td>
                    <td>৳ {{ $sale->shipping }}</td>
                    <td><strong>৳ {{ $sale->grand_total }}</strong></td>
                    <td>
                        <span class="badge bg-success">{{ ucfirst($sale->status) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No sales found</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <div class="card p-3 mt-4">
        <h5>Sales & Profit Chart</h5>
        <canvas id="salesChart" height="120"></canvas>
    </div>

    <div class="col-md-3">
        <div class="card bg-dark text-white p-3">
            <h4>৳ {{ number_format($total_profit, 2) }}</h4>
            <small>Total Profit</small>
        </div>
    </div>


</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('livewire:load', function () {

    Livewire.on('refreshChart', function (data) {

        const ctx = document.getElementById('salesChart').getContext('2d');

        if (window.salesChart) {
            window.salesChart.destroy();
        }

        window.salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        type: 'bar',
                        label: 'Sales (৳)',
                        data: data.sales,
                    },
                    {
                        type: 'line',
                        label: 'Profit (৳)',
                        data: data.profit,
                    }
                ]
            }
        });
    });
});
</script>
