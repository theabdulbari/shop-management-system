<div class="container mt-3">
    <div class="d-flex justify-content-between mb-3">
        <h2>Sales</h2>
        <a href="{{ route('sales.create') }}" class="btn btn-primary">Add Sale</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#Invoice</th>
            <th>Customer</th>
            <th>Sale Date</th>
            {{-- <th>User</th> --}}
            <th>Grand Total</th>
            <th>Paid</th>
            <th>Due</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sales as $s)
            <tr>
                <td>{{ $s->invoice->invoice_number }}</td>
                <td>{{ $s->customer?->name ?? 'Walk-in' }}</td>
                <td>{{ $s->sale_date }}</td>
                {{-- <td>{{ $s->user->name }}</td> --}}
                
                <td>{{ $s->grand_total }}</td>
                <td>{{ $s->paid }}</td>
                <td>{{ $s->due }}</td>
                <td>
                    <button wire:click="confirmDelete({{ $s->id }})" class="btn btn-danger btn-sm"
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                        Delete
                    </button>
                    <a class="btn btn-warning btn-sm" href="{{ route('sales.edit', $s->id) }}">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $sales->links() }}

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Are you sure?</h4>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button wire:click="delete" class="btn btn-danger" data-bs-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
