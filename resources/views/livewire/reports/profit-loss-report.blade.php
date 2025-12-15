<div class="container mt-3">

    <h3>Profit & Loss Report</h3>

    <div class="row mb-3">
        <div class="col-md-3">
            <input type="date" wire:model.live="from_date" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="date" wire:model.live="to_date" class="form-control">
        </div>
    </div>

    @if($data)
        <table class="table table-bordered">
            <tr><th>Total Sales</th><td>{{ number_format($data['sales'],2) }}</td></tr>
            <tr><th>Total Purchase</th><td>{{ number_format($data['purchase'],2) }}</td></tr>
            <tr><th>Total Expense</th><td>{{ number_format($data['expense'],2) }}</td></tr>
            <tr class="table-success">
                <th>Net Profit</th>
                <td>{{ number_format($data['profit'],2) }}</td>
            </tr>
        </table>
    @endif
</div>
