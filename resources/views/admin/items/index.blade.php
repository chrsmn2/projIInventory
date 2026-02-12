@extends('layouts.admin')

@section('title', 'Items')

@section('content')
@php
    /**
     * Sort ASC DESC
     */
    function sortUrl($column) {
        $isActive = request('sort_by') === $column;
        $dir = ($isActive && request('sort_dir') === 'asc') ? 'desc' : 'asc';

        return route('admin.items.index', array_merge(request()->all(), [
            'sort_by'  => $column,
            'sort_dir' => $dir,
        ]));
    }

    function sortIcon($column) {
        if (request('sort_by') !== $column) return '⇅';
        return request('sort_dir') === 'asc' ? '▲' : '▼';
    }
@endphp

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Items</h1>
            <p class="text-gray-600 mt-1">Manage inventory items</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.items.import.show') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 text-white font-semibold rounded-lg hover:bg-amber-700">
                Import Excel
            </a>

            <a href="{{ route('admin.items.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
                + Add Item
            </a>
        </div>
    </div>

    <!--FLASH MESSAGE-->
    @if (session('success'))
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <form method="GET" id="filterForm" class="flex flex-col sm:flex-row gap-3">

            <!-- Search -->
            <div class="flex flex-1 gap-2">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Search items..."
                       class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                </button>
            </div>

            <!--Reset-->
            <a href="{{ route('admin.items.index') }}"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500 text-center">
                Reset
            </a>

            <!--PerPage-->
            <div class="w-40">
                <select name="per_page"
                        onchange="document.getElementById('filterForm').submit();"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    @foreach ([5,10,25,50] as $size)
                        <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                            Show {{ $size }}
                        </option>
                    @endforeach
                </select>
            </div>

        </form>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">

            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-center">No</th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('item_code') }}"
                               class="inline-flex items-center gap-1 hover:text-blue-600">
                                Code <span class="text-xs">{{ sortIcon('item_code') }}</span>
                            </a>
                        </th>

                        <th class="px-6 py-4 text-left">
                            <a href="{{ sortUrl('item_name') }}"
                               class="inline-flex items-center gap-1 hover:text-blue-600">
                                Name <span class="text-xs">{{ sortIcon('item_name') }}</span>
                            </a>
                        </th>

                        <th class="px-4 py-3 text-left">
                            <a href="{{ sortUrl('category_name') }}"
                               class="inline-flex items-center gap-1 hover:text-blue-600">
                                Category <span class="text-xs">{{ sortIcon('category_name') }}</span>
                            </a>
                        </th>

                        <th class="px-4 py-3 text-center">Condition</th>
                        <th class="px-4 py-3 text-left">Description</th>

                        <th class="px-6 py-4 text-center">
                            <a href="{{ sortUrl('min_stock') }}"
                               class="inline-flex items-center gap-1 hover:text-blue-600">
                                Min Stock <span class="text-xs">{{ sortIcon('min_stock') }}</span>
                            </a>
                        </th>

                        <th class="px-6 py-4 text-center">
                            <a href="{{ sortUrl('stock') }}"
                               class="inline-flex items-center gap-1 hover:text-blue-600">
                                Stock <span class="text-xs">{{ sortIcon('stock') }}</span>
                            </a>
                        </th>

                        <th class="px-4 py-3 text-left">
                            <a href="{{ sortUrl('unit_name') }}"
                               class="inline-flex items-center gap-1 hover:text-blue-600">
                                Unit <span class="text-xs">{{ sortIcon('unit_name') }}</span>
                            </a>
                        </th>

                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                @forelse ($items as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-center">
                            {{ $items->firstItem() + $loop->index }}
                        </td>

                        <td class="px-4 py-3 font-mono text-blue-600 font-semibold">
                            {{ $item->item_code }}
                        </td>

                        <td class="px-4 py-3 font-medium">
                            {{ $item->item_name }}
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $item->category->category_name ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $item->conditionBadge() }}">
                                {{ ucfirst($item->condition) }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                            {{ $item->description ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-center font-medium">
                            {{ $item->min_stock }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span class="font-medium {{ $item->stock <= $item->min_stock ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $item->stock }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $item->unit->unit_name ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.items.edit', $item) }}"
                                   class="p-1 text-blue-600 hover:text-blue-800" title="Edit">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                </a>

                                <form method="POST" action="{{ route('admin.items.destroy', $item) }}"
                                      onsubmit="return confirm('Delete this item?')"
                                      style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-red-600 hover:text-red-800" title="Delete">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-10 text-gray-400">
                            No items found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- ================= PAGINATION ================= --}}
        <div class="px-6 py-4 border-t bg-gray-50 flex flex-col sm:flex-row sm:justify-between gap-4">
            <p class="text-sm text-gray-600">
                Showing
                <strong>{{ $items->firstItem() ?? 0 }}</strong>
                to
                <strong>{{ $items->lastItem() ?? 0 }}</strong>
                of
                <strong>{{ $items->total() }}</strong>
                items
            </p>

            {{ $items->links('pagination::tailwind') }}
        </div>
    </div>

</div>
@endsection

