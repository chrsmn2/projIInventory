@extends('layouts.admin')

@section('title', 'Stock Report')

@section('content')

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Stock Report</h1>
            <!--<p class="text-gray-600 mt-1">Inventory stock levels and analysis</p>-->
        </div>
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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
            <p class="text-xs text-gray-500 font-medium uppercase">Total Items</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalItems }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
            <p class="text-xs text-gray-500 font-medium uppercase">Total Stock</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalStock }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
            <p class="text-xs text-gray-500 font-medium uppercase">Low Stock</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $lowStockCount }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
            <p class="text-xs text-gray-500 font-medium uppercase">Out of Stock</p>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $outOfStockCount }}</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter & Export</h3>
        
        <form method="GET" id="filterForm" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search Item</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Report Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                    <select name="report_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="all" {{ request('report_type', 'all') == 'all' ? 'selected' : '' }}>All Items</option>
                        <option value="low" {{ request('report_type') == 'low' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ request('report_type') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="damaged" {{ request('report_type') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                    </select>
                </div>

                <!-- Condition -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Condition</label>
                    <select name="condition" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">All Conditions</option>
                        <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Good</option>
                        <option value="damaged" {{ request('condition') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                    </select>
                </div>

                <!-- Per Page -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Per Page</label>
                    <select name="per_page" onchange="document.getElementById('filterForm').submit();"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @foreach([10,15,25,50] as $size)
                            <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                                {{ $size }} items
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2 pt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>

                <a href="{{ route('admin.reports.stock') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </a>

                <!-- Export Button -->
                <button type="submit" name="export" value="excel" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Export Excel
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
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
                        <th class="px-6 py-4 text-center font-semibold text-gray-900">Stock</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-900">Min Stock</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-900">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($items as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 font-medium">
                                {{ ($items->currentPage() - 1) * $items->perPage() + $loop->index + 1 }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-blue-600 font-semibold">{{ $item->item_code }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-900 font-medium">
                                {{ $item->item_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $item->category?->category_name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $item->condition == 'good' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                    {{ ucfirst($item->condition) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold {{ $item->stock == 0 ? 'text-red-600' : ($item->stock < $item->min_stock ? 'text-yellow-600' : 'text-green-600') }}">
                                {{ $item->stock }}
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700">
                                {{ $item->min_stock }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($item->stock == 0)
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">Out of Stock</span>
                                @elseif ($item->stock < $item->min_stock)
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">Low Stock</span>
                                @elseif ($item->condition == 'damaged')
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-orange-100 text-orange-800">Damaged</span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">Available</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="font-medium">No items found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $items->links() }}
    </div>
</div>

@endsection