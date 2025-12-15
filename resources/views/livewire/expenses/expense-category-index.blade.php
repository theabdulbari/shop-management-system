<div class="container mt-3">
    <h3>Expense Categories</h3>

    <form wire:submit.prevent="save" class="mb-3">
        <input type="text" wire:model="name" placeholder="Category Name" class="form-control mb-2">
        <textarea wire:model="note" class="form-control mb-2" placeholder="Note"></textarea>

        <select wire:model="status" class="form-select mb-2">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>

        <button class="btn btn-primary">Save</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th width="120">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <button wire:click="edit({{ $cat->id }})" class="btn btn-sm btn-warning">Edit</button>
                        <button wire:click="delete({{ $cat->id }})" class="btn btn-sm btn-danger">Del</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
