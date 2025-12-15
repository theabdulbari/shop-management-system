<div class="container mt-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Expenses</h3>
        <a href="{{ route('expense.categories') }}" class="btn btn-secondary">
            Expense Categories
        </a>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary">
            + Add Expense
        </a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>User</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $index => $expense)
                        <tr>
                            <td>{{ $expenses->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                            <td>{{ $expense->title }}</td>
                            <td>{{ $expense->category->name ?? '-' }}</td>
                            <td class="fw-bold text-end">
                                {{ number_format($expense->amount, 2) }}
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ ucfirst($expense->payment_method) }}
                                </span>
                            </td>
                            <td>{{ $expense->user->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('expenses.edit', $expense->id) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-3">
                                No expenses found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenses->hasPages())
            <div class="card-footer">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</div>
