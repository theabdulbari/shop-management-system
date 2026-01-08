<!-- resources/views/livewire/loan/loan-index.blade.php -->

<div class="container mt-3">

    <div class="d-flex justify-content-between mb-3">
        <h4>Loan List</h4>
        <a href="{{ route('loans.create') }}" class="btn btn-primary">Add Loan</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" class="form-control"
                   wire:model.debounce.300ms="search"
                   placeholder="Search name or phone">
        </div>

        <div class="col-md-3">
            <select class="form-control" wire:model.lazy="status">
                <option value="">All Status</option>
                <option value="due">Due</option>
                <option value="partial">Partial</option>
                <option value="paid">Paid</option>
            </select>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Payer</th>
            <th>Loan Date</th>
            <th>Amount</th>
            <th>Paid</th>
            <th>Due</th>
            <th>Status</th>
            <th width="25%">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($loans as $loan)
            <tr>
                <td>
                    <div>{{ $loan->payer_name }}</div>
                    <small style="font-size:11px;"><a href="tel:{{ $loan->phone }}">{{ $loan->phone }}</a></small>
                </td>
                <td>{{ $loan->loan_date->format('d-M-Y') }}</td>
                <td>{{ number_format($loan->amount, 2) }}</td>
                <td>{{ number_format($loan->paid, 2) }}</td>
                <td>{{ number_format($loan->due, 2) }}</td>
                <td>
                    <select class="form-select form-select-sm
                        @if($loan->status=='paid') border-success
                        @elseif($loan->status=='partial') border-warning
                        @else border-danger @endif"
                        wire:change="updateStatus({{ $loan->id }}, $event.target.value)">
                        <option value="due" @selected($loan->status === 'due')>Due</option>
                        <option value="partial" @selected($loan->status === 'partial')>Partial</option>
                        <option value="paid" @selected($loan->status === 'paid')>Paid</option>
                    </select>

                </td>
                <td>
                    <button class="btn btn-sm btn-success"
                            wire:click="openPaymentModal({{ $loan->id }})"
                            data-bs-toggle="modal" @if($loan->status === 'paid') disabled @endif
                            data-bs-target="#addPaymentModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5.5h5.5a.5.5 0 0 1 0 1H8.5v5.5a.5.5 0 0 1-1 0V8.5H2a.5.5 0 0 1 0-1h5.5V2.5A.5.5 0 0 1 8 2Z"/>
                            </svg>
                    </button>
                    <button class="btn btn-sm btn-info"
                            wire:click="viewPayments({{ $loan->id }})"
                            data-bs-toggle="modal"
                            data-bs-target="#paymentModal">
                            Payments
                    </button>
                    <a href="{{ route('loans.edit',$loan->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <button wire:click="confirmDelete({{ $loan->id }})"
                            class="btn btn-sm btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                        Delete
                    </button>
                    
                </td> 
            </tr>
            <tr>
                <td colspan="4"><b>Note:</b> {{ $loan->note }}</td>
                <td><b>P.P.Date:</b> {{ $loan->possible_paid_date->format('d-M-Y') }}</td>
                <td colspan="2"><b>Address:</b> {{ $loan->address }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $loans->links() }}

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    Are you sure?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" wire:click="delete" data-bs-dismiss="modal">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Payment History Modal -->
<div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Payment History —
                    {{ $selectedLoan?->payer_name }}
                </h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                @if(empty($payments))
                    <p class="text-muted">No payments found.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Note</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->paid_date->format('d-m-Y') }}</td>
                                <td>{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->note ?? '-' }}</td>
                                <td>
                                <button  wire:click="deletePayment({{ $payment->id }})"
                                        class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div wire:ignore.self class="modal fade" id="addPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Add Payment — {{ $selectedLoan?->payer_name }}
                </h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-2">
                    <label>Amount</label>
                    <input type="number" step="0.0001"
                           class="form-control"
                           wire:model.defer="paymentAmount">
                    @error('paymentAmount') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-2">
                    <label>Paid Date</label>
                    <input type="date"
                           class="form-control"
                           wire:model.defer="paymentDate">
                </div>

                <div class="mb-2">
                    <label>Note</label>
                    <textarea class="form-control"
                              wire:model.defer="paymentNote"></textarea>
                </div>

                <p class="text-muted">
                    Current Due: {{ number_format($selectedLoan?->due ?? 0, 2) }}
                </p>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" wire:click="savePayment">
                    Save Payment
                </button>
            </div>

        </div>
    </div>
</div>



</div>


<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('close-payment-modal', () => {

        const paymentModalEl = document.getElementById('paymentModal');
        const addPaymentModalEl = document.getElementById('addPaymentModal');

        if (paymentModalEl) {
            const paymentModal = bootstrap.Modal.getInstance(paymentModalEl)
                || new bootstrap.Modal(paymentModalEl);
            paymentModal.hide();
        }

        if (addPaymentModalEl) {
            const addPaymentModal = bootstrap.Modal.getInstance(addPaymentModalEl)
                || new bootstrap.Modal(addPaymentModalEl);
            addPaymentModal.hide();
        }

    });
});

</script>