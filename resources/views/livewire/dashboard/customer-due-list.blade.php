<div class="card shadow-sm mb-3">
    <div class="card-header fw-bold">
        💰 Customer Due List (Total Due: {{ currency() }} {{ number_format($totalDue, 2) }})
    </div>

    <div class="list-group list-group-flush">
        @foreach($customers as $customer)
            <div class="list-group-item">

                <!-- Customer Row -->
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $customer->name }}</strong>
                        <div class="text-muted small">
                            Total Due: {{ currency() }} {{ number_format($customer->total_due, 2) }}
                        </div>
                    </div>

                    <button class="btn btn-sm btn-outline-primary"
                            wire:click="toggle({{ $customer->id }})">
                        {{ $expandedCustomer === $customer->id ? '−' : '+' }}
                    </button>
                </div>

                <!-- Invoice Details -->
                @if($expandedCustomer === $customer->id)
                    <div class="mt-3">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th class="text-end">Due Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->sales as $sale)
                                    <tr>
                                        <td>
                                            {{ $sale->invoice?->invoice_number ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ date('d/m/Y', strtotime($sale->sale_date)) }}
                                        </td>
                                        <td class="text-end text-danger">
                                            {{ currency() }} {{ number_format($sale->due, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        @endforeach

        @if($customers->isEmpty())
            <div class="list-group-item text-center text-muted">
                ✅ No due customers
            </div>
        @endif
    </div>
</div>
