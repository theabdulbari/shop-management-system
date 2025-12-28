<div class="card mb-3">
       <div class="card-header d-flex align-items-center gap-2">
        <strong>📊 Sales / Expense / Profit</strong>
        
        <select wire:model="range" class="form-select w-auto">
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>
    </div>

    <div style="height: 350px;">
        <canvas id="sepChart"></canvas>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            let ctx = document.getElementById('sepChart').getContext('2d');

            let chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        { label: 'Sales', data: [], borderWidth: 2 },
                        { label: 'Expenses', data: [], borderWidth: 2 },
                        { label: 'Profit', data: [], borderWidth: 2 },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            Livewire.on('chart-update', (data) => {
                chart.data.labels = data.labels;
                chart.data.datasets[0].data = data.sales;
                chart.data.datasets[1].data = data.expenses;
                chart.data.datasets[2].data = data.profits;
                chart.update();
            });
        });
    </script>
</div>
