<?php

namespace App\Livewire\Traits;

trait WithUniversalSearch
{
    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    /**
     * Apply search to query
     */
    public function applySearch($query, array $columns)
    {
        if (!$this->search) {
            return $query;
        }

        return $query->where(function ($q) use ($columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', '%' . $this->search . '%');
            }
        });
    }
}
