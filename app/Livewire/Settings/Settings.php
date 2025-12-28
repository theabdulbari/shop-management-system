<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Livewire\WithPagination;

class Settings extends Component
{
    use WithFileUploads, WithPagination;

    public $system_name;
    public $logo;
    public $db_backup_path;
    public $setting;
    public $currency_symbol;
    public $backupRunning = false;

    protected $rules = [
        'system_name' => 'required|string|max:255',
        'logo' => 'nullable|image|mimes:png|max:2048',
        'currency_symbol' => 'required|string|max:1',
        // 'db_backup_path' => 'required|string',
    ];

    public function mount()
    {
        $this->setting = Setting::firstOrCreate([]);

        $this->system_name = $this->setting->system_name;
        $this->db_backup_path = $this->setting->db_backup_path;
        $this->currency_symbol = $this->setting->currency_symbol;
    }

public function save()
{
    $this->validate();

    if ($this->logo) {

        // Delete old logo if exists
        if ($this->setting->logo && Storage::disk('public')->exists($this->setting->logo)) {
            Storage::disk('public')->delete($this->setting->logo);
        }

        // Always save as "logo.ext"
        $extension = $this->logo->getClientOriginalExtension();
        $fileName = 'logo.' . $extension;

        // Store in public/logos
        $path = $this->logo->storeAs('logos', $fileName, 'public');

        $this->setting->logo = $path;
    }

    $this->setting->update([
        'system_name' => $this->system_name,
        'currency_symbol' => $this->currency_symbol,
        // 'db_backup_path' => $this->db_backup_path,
    ]);

    // Clear cache if using settings cache
    cache()->forget('app_setting');

    session()->flash('success', 'Settings updated successfully.');
}


    public function backupNow()
    {
        $path = storage_path('app/private/backups');

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $filename = 'backup_' . now()->format('Y_m_d_His') . '.sql';
        $fullPath = $path . '/' . $filename;

        $username = escapeshellarg(config('database.connections.mysql.username'));
        $password = escapeshellarg(config('database.connections.mysql.password'));
        $database = escapeshellarg(config('database.connections.mysql.database'));
        $backupPath = escapeshellarg($fullPath);

        $command = "mysqldump -u$username --password=$password $database > $backupPath";

        // Run command
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            session()->flash('error', 'Database backup failed.');
        } else {
            session()->flash('success', 'Database backup created successfully.');
        }
    }

    // public function backupNow()
    // {
    //     try {
    //         // Run Spatie backup only for DB
    //         Artisan::call('backup:run', [
    //             '--only-db' => true,
    //         ]);

    //         session()->flash('success', 'Database backup completed successfully.');

    //     } catch (\Exception $e) {
    //         session()->flash('error', 'Backup failed: ' . $e->getMessage());
    //     }
    // }

    public function downloadBackup($file)
    {
        $path = storage_path('app/backups/'.$file);
        if (file_exists($path)) {
            return response()->download($path);
        }
        session()->flash('error', 'Backup file not found.');
    }

    public function deleteBackup($file)
    {
        if (Storage::disk('local')->exists('backups/'.$file)) {
            Storage::disk('local')->delete('backups/'.$file);
            session()->flash('success', 'Backup deleted successfully.');
        } else {
            session()->flash('error', 'Backup file not found.');
        }
    }

    public function getBackupsProperty()
    {
        $files = Storage::disk('local')->files('backups');
        // dd($files);
        return collect($files)
            ->filter(fn($f) => str_ends_with($f, '.zip') || str_ends_with($f, '.sql'))
            ->sortByDesc(fn($f) => Storage::disk('local')->lastModified($f));
    }


    public function render()
    {
        return view('livewire.settings.settings');
    }
}
