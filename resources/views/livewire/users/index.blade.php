<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h3>Users</h3>
        <a href="{{ route('users.create') }}" class="btn btn-primary">Add User</a>
    </div>

    <input type="text" wire:model="search" class="form-control mb-3" placeholder="Search users..." />

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th width="150">Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <button wire:click="deleteUser({{ $user->id }})" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</div>
