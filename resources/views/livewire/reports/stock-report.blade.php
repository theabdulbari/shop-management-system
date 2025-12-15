<div class="container mt-4">

    <h2 class="mb-3">Stock Report</h2>

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body row">

            <div class="col-md-4">
                <input type="text" wire:model.live="search" class="form-control"
                       placeholder="Search product...">
            </div>

            <div class="col-md-4">
                <select wire:model.live="category" class="form-control">
                    <option value="">All Categories</option>
                    @foreach(App\Models\Category::all() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" wire:model.live="low_stock_only">
                    <label class="form-check-label">Show Low Stock Only (<= 5)</label>
                </div>
            </div>

        </div>
    </div>

    <!-- Summary -->
    <div class="alert alert-info">
        <strong>Total Stock Value:</strong> {{ number_format($total_value, 2) }} Tk
    </div>

    <!-- Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="bg-dark text-white">
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th width="120">Stock</th>
                    <th width="120">Price</th>
                    <th width="150">Total Value</th>
                </tr>
                </thead>

                <tbody>
                @forelse($products as $p)
                    <tr class="{{ $p->stock <= 5 ? 'table-danger' : '' }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->category->name ?? '' }}</td>
                        <td>{{ $p->stock }}</td>
                        <td>{{ number_format($p->price, 2) }}</td>
                        <td>{{ number_format($p->stock * $p->price, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No products found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $products->links() }}
        </div>
    </div>

</div>
