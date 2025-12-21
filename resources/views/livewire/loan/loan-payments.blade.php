<!-- resources/views/livewire/loan/loan-payments.blade.php -->

<div class="container">
    <h4>Payment History - {{ $loan->payer_name }}</h4>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Date</th>
            <th>Amount</th>
        </tr>
        </thead>
        <tbody>
        @forelse($loan->payments as $payment)
            <tr>
                <td>{{ $payment->paid_date }}</td>
                <td>{{ number_format($payment->amount,2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-center">No payments found</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <a href="{{ route('loans.index') }}" class="btn btn-secondary">Back</a>
</div>
