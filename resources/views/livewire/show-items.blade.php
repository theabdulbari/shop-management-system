<div>
    <div class="mb-4">
        <label for="perPage" class="block text-sm font-medium text-gray-700">Items per page:</label>
        <select id="perPage" wire:model.live="perPage" class="mt-1 block w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            @foreach ($perPageOptions as $option)
                <option value="{{ $option }}">{{ ucfirst($option) }}</option>
            @endforeach
        </select>
    </div>

    {{-- Your items table/list goes here --}}
    @foreach ($items as $item)
        <p>{{ $item->name }}</p>
    @endforeach

    {{-- Pagination links --}}
    @if ($perPage != 'all')
        <div class="mt-4">
            {{ $items->links() }}
        </div>
    @endif
</div>