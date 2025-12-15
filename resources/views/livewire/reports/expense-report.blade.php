<div class="container mt-3">

    <h3>Expense Report</h3>

    <div class="row mb-3">
        <div class="col-md-3">
            <input type="date" wire:model.live="from_date" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="date" wire:model.live="to_date" class="form-control">
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Category</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $e)
                <tr>
                    <td>{{ $e->expense_date }}</td>
                    <td>{{ $e->title }}</td>
                    <td>{{ $e->category->name ?? '-' }}</td>
                    <td class="text-end">{{ number_format($e->amount,2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h5>Total Expense: {{ number_format($total,2) }}</h5>

    {{ $expenses->links() }}
</div>
