<!-- resources/views/livewire/settings/settings-module.blade.php -->

<div class="container mt-4">
    <h4>System Settings</h4>

   
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label>System Name *</label>
            <input type="text" class="form-control" wire:model.defer="system_name">
        </div>

        <div class="mb-3">
            <label>System Logo</label>
            <input type="file" class="form-control" wire:model="logo">
        </div>
        <div class="mb-3">
            <label class="form-label">Currency Symbol</label>
            <input type="text"
                name="currency_symbol"
                 wire:model.defer="currency_symbol"
                class="form-control"
                placeholder="৳, $, €, £"
                maxlength="5">
        </div>

        {{-- <div class="mb-3">
            <label>Database Backup Path *</label>
            <input type="text" class="form-control"
                   placeholder="/storage/backups"
                   wire:model.defer="db_backup_path">
        </div> --}}

        <button class="btn btn-primary">Save Settings</button>
    </form>

    <hr>

 <!-- Backup Controls -->
     <h4 class="mt-5">Database Backup  <small>Path: storage/app/private/backups</small></h4>

    <div class="mb-3">
       
        <button class="btn btn-danger" wire:click="backupNow" @disabled($backupRunning)>
            {{ $backupRunning ? 'Backing up...' : 'Backup Now' }}
        </button>
    </div>

    <!-- Backup List -->
    <h5>Existing Backups</h5>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>File Name</th>
                <th>Size</th>
                <th>Last Modified</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($this->backups as $file)
                <tr>
                    <td>{{ basename($file) }}</td>
                    <td>{{ number_format(Storage::disk('local')->size($file)/1024, 2) }} KB</td>
                    <td>{{ date('Y-m-d H:i:s', Storage::disk('local')->lastModified($file)) }}</td>
                    <td>
                        <button class="btn btn-sm btn-success" wire:click="downloadBackup('{{ basename($file) }}')">Download</button>
                        <button class="btn btn-sm btn-danger" wire:click="deleteBackup('{{ basename($file) }}')">Delete</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No backups found.</td></tr>
            @endforelse
        </tbody>
    </table>
    
</div>
