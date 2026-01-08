<div class="container mt-3">

    <div class="d-flex justify-content-between mb-3">
        <h2>Suppliers</h2>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Add Supplier</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mb-3">
        <div class="col-md-4">
            <input
                type="text"
                class="form-control"
                placeholder="Search supplier by name..."
                wire:model.lazy="search">
        </div>

        <div class="col-md-2">
            <select class="form-select" wire:model.lazy="perPage">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="all">All</option>
            </select>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Description</th>
            <th>Phone</th>
            <th>Status</th>
            <th width="20%">Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach($suppliers as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>{{ $s->name }}</td>
                <td>{{ $s->details }}</td>
                <td>{{ $s->phone }}</td>
                <td>
                    <span class="badge bg-{{ $s->status ? 'success' : 'secondary' }}">
                        {{ $s->status ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('suppliers.edit', $s->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <button wire:click="confirmDelete({{ $s->id }})"
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                        Delete
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="card-footer d-flex justify-content-center">
        @if($perPage !== 'all')
            {{ $suppliers->links('pagination::bootstrap-5') }}
        @endif
    </div>

    <!-- Delete modal -->
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
