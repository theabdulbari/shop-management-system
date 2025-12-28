<div class="card mb-3">
    <div class="card-header d-flex align-items-center gap-2">
        <strong>📊 Sales Overview</strong>
        
        <select wire:model.lazy="type" class="form-select form-select-sm w-auto">
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>
    </div>
    
    <div class="card-body">
        <div wire:ignore class="chart-wrapper">
            <canvas id="salesChart" height="320"></canvas>
        </div>
    </div>

    @if(env('APP_DEBUG'))
    {{-- <div class="card-footer">
        <small class="text-muted">
            <strong>Debug:</strong> {{ count($values) }} data points loaded
            <strong>Debug:</strong> {{ $type }} type loaded
        </small>
    </div> --}}
    @endif
</div>

<!-- Load Chart.js BEFORE your script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let salesChart = null;

// Wait for everything to load
window.addEventListener('DOMContentLoaded', function() {
   console.log('DOM loaded, Chart available:', typeof Chart);
    createChart();
});

function createChart(labels = [], values = []) {
    const canvas = document.getElementById('salesChart');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    
    // Destroy existing chart
    if (salesChart) {
        salesChart.destroy();
    }
    
    // Get data from PHP
    if (!labels && !values) {
    const labels = @json($labels);
    const values = @json($values);
    }
   console.log('Creating chart with:', { labels, values });
    
    salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels || [],
            datasets: [{
                label: 'Sales',
                data: values || [],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Listen for Livewire updates
// document.addEventListener('livewire:load', () => {
//     window.addEventListener('chartDataUpdated', event => {
//         const { labels, values } = event.detail;
//        console.log('Chart data received:', labels, values);
//         createChart(labels, values);
//     });
// });

// document.addEventListener('livewire:load', function () {
//     Livewire.on('chartDataUpdated', (labels, values) => {
//         console.log('Chart data received:', labels, values);
//         createChart(labels, values);
//     });
// });

document.addEventListener('livewire:init', () => {
    Livewire.on('chartDataUpdated', (event) => {
        console.log(event.labels, event.values);
        createChart(event.labels, event.values);
    });
});

</script>