<div class="container mt-3">

    <h2>Dashboard & Analytics</h2>

    <h5>{{ $this->dailySales }}</h5>
    <h5>{{ $this->weeklySales }}</h5>
    <h5>{{ $this->monthlySales }}</h5>
    <h5>{{ $this->totalStockValue }}</h5>
    <h5>{{ $this->lowStockCount }}</h5>

    <div wire:poll.5000ms>
        <h5>Daily Sales: {{ $this->dailySales }}</h5>
        <h5>Total Stock Value: {{ $this->totalStockValue }}</h5>
    </div>

    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Daily Sales</h5>
                    <p class="card-text">{{ $dailySales }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Weekly Sales</h5>
                    <p class="card-text">{{ $weeklySales }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Monthly Sales</h5>
                    <p class="card-text">{{ $monthlySales }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Total Stock Value</div>
                <div class="card-body">
                    <h5>{{ $totalStockValue }}</h5>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Low Stock Count</div>
                <div class="card-body">
                    <h5>{{ $lowStockCount }}</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional: Add charts with Chart.js for Sales trends -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">Sales Trend (Last 30 Days)</div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <livewire:dashboard.low-stock-alert />


</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                @for($i = 30; $i >= 0; $i--)
                    "{{ \Carbon\Carbon::today()->subDays($i)->format('d M') }}",
                @endfor
            ],
            datasets: [{
                label: 'Sales',
                data: [
                    @for($i = 30; $i >= 0; $i--)
                        {{ \App\Models\Sale::whereDate('created_at', \Carbon\Carbon::today()->subDays($i))->sum('grand_total') }},
                    @endfor
                ],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush
