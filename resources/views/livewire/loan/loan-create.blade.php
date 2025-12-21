<div class="container mt-3">
    <h4 class="mb-3">Create Loan</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save">
        <div class="row">

            <div class="col-md-6 mb-2">
                <label>Payer Name *</label>
                <input type="text" class="form-control" wire:model.lazy="payer_name">
                @error('payer_name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-3 mb-2">
                <label>Phone</label>
                <input type="text" class="form-control" wire:model.lazy="phone">
            </div>

            <div class="col-md-3 mb-2">
                <label>Loan Date *</label>
                <input type="date" class="form-control" wire:model="loan_date">
                @error('loan_date') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 mb-2">
                <label>Address</label>
                <textarea class="form-control" wire:model.lazy="address"></textarea>
            </div>

            <div class="col-md-3 mb-2">
                <label>Loan Amount *</label>
                <input type="number" step="0.0001" class="form-control" wire:model.lazy="amount">
                @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-3 mb-2">
                <label>Paid Amount</label>
                <input type="number" step="0.0001" class="form-control" wire:model.lazy="paid">
            </div>

            @if($amount)
                <div class="alert alert-info mt-2">
                    Due:
                    {{ number_format(($amount - ($paid ?? 0)), 2) }}
                </div>
            @endif


            <div class="col-md-3 mb-2">
                <label>Possible Paid Date</label>
                <input type="date" class="form-control" wire:model="possible_paid_date">
            </div>

            <div class="col-md-9 mb-2">
                <label>Note</label>
                <textarea class="form-control" wire:model.lazy="note"></textarea>
            </div>

        </div>

        <div class="mt-3">
            <button class="btn btn-success">Save Loan</button>
            <a href="{{ route('loans.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
