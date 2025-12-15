<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Monthly Expense Chart</h4>

        <select wire:model="year" class="form-select w-auto">
            @for($y = now()->year; $y >= now()->year - 5; $y--)
                <option value="{{ $y }}">{{ $y }}</option>
            @endfor
        </select>
    </div>

    <div class="card">
        <div class="card-body">
            <canvas id="expenseChart" height="100"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let expenseChart;

    document.addEventListener('livewire:init', () => {

        const ctx = document.getElementById('expenseChart').getContext('2d');

        expenseChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels'] ?? []),
                datasets: [{
                    label: 'Monthly Expense',
                    data: @json($chartData['data'] ?? []),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        Livewire.on('expense-chart-updated', data => {
            expenseChart.data.labels = data.labels;
            expenseChart.data.datasets[0].data = data.data;
            expenseChart.update();
        });
    });
</script>
@endpush
