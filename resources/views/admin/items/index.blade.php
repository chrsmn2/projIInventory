@extends('layouts.admin')

@section('title', 'Items')

@section('content')

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Items</h1>
            <p class="text-gray-600 mt-1">Manage inventory items</p>
        </div>

        <a href="{{ route('admin.items.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add Item
        </a>
    </div>

    @if (session('success'))
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex">
                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search items..."
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Search
                    </button>
                </form>
            </div>

            <div class="sm:w-48">
                <form method="GET">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <select name="per_page" onchange="this.form.submit()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach([5,10,25,50] as $size)
                            <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                                Show {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Code</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Item Name</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Category</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-900">Condition</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Description</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-900">Min Stock</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-900">Stock</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Unit</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($items as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 font-medium">
                                {{ $items->firstItem() + $loop->index }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="font-mono text-blue-600 font-semibold">{{ $item->item_code }}</span>
                            </td>

                            <td class="px-6 py-4 text-gray-900 font-medium">
                                {{ $item->item_name }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $item->category->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($item->condition === 'normal') bg-green-100 text-green-800
                                    @elseif($item->condition === 'damaged') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($item->condition) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                                {{ $item->description ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-center text-gray-900 font-medium">
                                {{ $item->min_stock }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="font-medium {{ $item->stock <= $item->min_stock ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $item->stock }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $item->unit->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.items.edit', $item) }}"
                                    class="p-1 text-blue-600 hover:text-blue-800 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </a>

                                    <form method="POST" action="{{ route('admin.items.destroy', $item) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this item?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-red-600 hover:text-red-800 transition-colors">
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
                            <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <p class="text-lg font-medium mb-1">No items found</p>
                                    <p class="text-sm">Get started by creating your first item</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <p class="text-sm text-gray-600">
                Showing <span class="font-semibold">{{ $items->firstItem() ?? 0 }}</span>
                to <span class="font-semibold">{{ $items->lastItem() ?? 0 }}</span>
                of <span class="font-semibold">{{ $items->total() }}</span> items
            </p>

            <div class="flex justify-center">
                {{ $items->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

@endsection

