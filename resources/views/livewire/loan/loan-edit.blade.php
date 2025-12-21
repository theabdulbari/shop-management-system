<!-- resources/views/livewire/loan/loan-edit.blade.php -->

<div class="container mt-3">
    <h4 class="mb-3">Edit Loan</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

<form wire:submit.prevent="update">
    <div class="row">

        <div class="col-md-6 mb-2">
            <label>Payer Name *</label>
            <input type="text" class="form-control"
                   wire:model.defer="form.payer_name">
        </div>

        <div class="col-md-3 mb-2">
            <label>Phone</label>
            <input type="text" class="form-control"
                   wire:model.defer="form.phone">
        </div>

        <div class="col-md-3 mb-2">
            <label>Loan Date *</label>
            <input type="date" class="form-control" wire:model.defer="form.loan_date">
        </div>

        <div class="col-md-6 mb-2">
            <label>Address</label>
            <textarea class="form-control"
                      wire:model.defer="form.address"></textarea>
        </div>

        <div class="col-md-3 mb-2">
            <label>Loan Amount *</label>
            <input type="number" step="0.0001"
                   class="form-control"
                   wire:model.defer="form.amount">
        </div>

        <div class="col-md-3 mb-2">
            <label>Paid Amount</label>
            <input type="number" step="0.0001"
                   class="form-control"
                   wire:model.defer="form.paid">
        </div>

        <div class="col-md-12 mt-2">
            <div class="alert alert-info">
                Due:
                {{ number_format(($form['amount'] ?? 0) - ($form['paid'] ?? 0), 2) }}
            </div>
        </div>

        <div class="col-md-3 mb-2">
            <label>Possible Paid Date</label>
            <input type="date" class="form-control" wire:model.defer="form.possible_paid_date">
        </div>
        
        <div class="col-md-9 mb-2">
            <label>Note</label>
            <textarea class="form-control"
                      wire:model.defer="form.note"></textarea>
        </div>

    </div>

    <div class="mt-3">
        <button class="btn btn-success">Update Loan</button>
        <a href="{{ route('loans.index') }}" class="btn btn-secondary">Back</a>
    </div>
</form>
</div>