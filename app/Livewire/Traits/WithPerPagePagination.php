<?php

namespace App\Livewire\Traits;

use Livewire\WithPagination;

trait WithPerPagePagination
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 25;

    protected $queryString = [
        'perPage' => ['except' => 25],
    ];

    public function mount()
    {
        $this->perPage = session('perPage', 25);
    }

    public function updatedPerPage()
    {
        session(['perPage' => $this->perPage]);
        $this->resetPage();
    }


    public function getPerPageOptionsProperty()
    {
        return [10, 20, 30, 50, 75, 100, 'all'];
    }
}
