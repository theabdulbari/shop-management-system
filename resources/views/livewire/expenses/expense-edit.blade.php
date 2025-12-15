<div class="container mt-3">
    <h3>Edit Expense</h3>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="update">

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Title</label>
                <input type="text" wire:model="expense.title" class="form-control">
                @error('expense.title') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label>Category</label>
                <select wire:model="expense.expense_category_id" class="form-select">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('expense.expense_category_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label>Date</label>
                <input type="date" wire:model="expense.expense_date" class="form-control">
                @error('expense.expense_date') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Amount</label>
                <input type="number" wire:model="expense.amount" class="form-control">
                @error('expense.amount') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label>Payment Method</label>
                <select wire:model="expense.payment_method" class="form-select">
                    <option value="cash">Cash</option>
                    <option value="bank">Bank</option>
                    <option value="mobile">Mobile</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label>Reference</label>
                <input type="text" wire:model="expense.reference" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label>Note</label>
            <textarea wire:model="expense.note" class="form-control"></textarea>
        </div>

        <div class="mt-3">
            <button class="btn btn-success">Update Expense</button>
            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                Back
            </a>
        </div>

    </form>
</div>
