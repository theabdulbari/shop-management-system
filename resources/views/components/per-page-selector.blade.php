<div class="d-flex justify-content-end mb-2">
    <div class="d-flex align-items-center gap-2">
        <label class="mb-0">Show</label>

        <select wire:model.live="perPage" class="form-select form-select-sm w-auto">
            @foreach($this->perPageOptions as $opt)
                <option value="{{ $opt }}">
                    {{ strtoupper($opt) }}
                </option>
            @endforeach
        </select>

        <span>entries</span>
    </div>
</div>
