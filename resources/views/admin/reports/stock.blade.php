@extends('layouts.admin')

@section('title', 'Stock Report')

@section('content')

<div class="bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between
                gap-4 px-6 py-4
                bg-gradient-to-r from-green-600 to-green-700 rounded-t-xl">

        <div>
            <h2 class="text-xl font-semibold text-white">Stock Report</h2>
            <p class="text-sm text-blue-100">
                Current inventory stock levels and analysis
            </p>
        </div>

        <!-- RIGHT SIDE: Search, Filter & Export -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full md:w-auto">

            <!-- LEFT SIDE: Search + Filter -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 flex-1">
                
                <!-- SEARCH -->
                <form method="GET" class="flex gap-2 flex-1 sm:flex-none">
                    <input type="text"
                        name="search"
                        value="{{ request('search', '') }}"
                        placeholder="Search item..."
                        class="px-3 py-2 border rounded-lg text-sm w-full sm:w-48 focus:ring focus:ring-green-200">

                    <button class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                        Search
                    </button>
                </form>

                <!-- CATEGORY FILTER -->
                <form method="GET" class="flex items-center">
                    <input type="hidden" name="search" value="{{ request('search', '') }}">
                    <select name="category"
                            onchange="this.form.submit()"
                            class="px-3 py-2 border rounded-lg text-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </form>

            </div>

            <!-- RIGHT SIDE: Export Button -->
            <button onclick="window.print()" 
                    class="inline-flex items-center justify-center gap-2
                           px-4 py-2 bg-green-600 text-white rounded-lg text-sm
                           hover:bg-green-700 transition">
                📥 Export PDF
            </button>

        </div>

    </div>

    <!-- STATS SECTION -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-6 border-b border-gray-200">
        
        <!-- Total Items -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
            <p class="text-sm text-gray-700 font-semibold mb-1">Total Items</p>
            <p class="text-3xl font-bold text-blue-700">{{ $totalItems }}</p>
        </div>

        <!-- Total Stock -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
            <p class="text-sm text-green-600 font-semibold mb-1">Total Stock</p>
            <p class="text-3xl font-bold text-green-700">{{ $totalStock }}</p>
        </div>

        <!-- Low Stock Items -->
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200">
            <p class="text-sm text-yellow-600 font-semibold mb-1">Low Stock (&lt;5)</p>
            <p class="text-3xl font-bold text-yellow-700">{{ $lowStockCount }}</p>
        </div>

        <!-- Zero Stock -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-4 border border-red-200">
            <p class="text-sm text-red-600 font-semibold mb-1">Out of Stock</p>
            <p class="text-3xl font-bold text-red-700">{{ $outOfStockCount }}</p>
        </div>

    </div>

    <!-- TABLE SECTION -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">

            <thead class="bg-gray-100 border-b border-gray-300 sticky top-0">
                <tr>
                    <th class="px-6 py-3 font-semibold text-gray-700">No</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Item Code</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Item Name</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Category</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Condition</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-700">Stock</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 font-semibold text-gray-700">Description</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse ($items as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3 text-gray-700">{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>

                    <td class="px-6 py-3 font-semibold text-gray-900">
                        <span class="inline-block px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-xs">
                            {{ $item->item_code }}
                        </span>
                    </td>

                    <td class="px-6 py-3 font-semibold text-gray-900">
                        <a href="{{ route('admin.items.show', $item->id) }}" class="text-gray-700 hover:underline">
                            {{ $item->item_name }}
                        </a>
                    </td>

                    <td class="px-6 py-3 text-gray-700">
                        <span class="inline-block px-2 py-1 bg-gray-200 text-gray-800 rounded text-xs">
                            {{ $item->category->category_name ?? '-' }}
                        </span>
                    </td>

                    <td class="px-6 py-3">
                        <span class="px-2 py-1 rounded text-xs font-bold
                            @if($item->condition == 'normal') bg-green-100 text-green-700
                            @elseif($item->condition == 'damaged') bg-red-100 text-red-700
                            @else bg-gray-200 text-gray-700 @endif">
                            {{ ucfirst($item->condition) }}
                        </span>
                    </td>

                    <td class="px-6 py-3 text-center font-bold">
                        <span class="inline-block px-3 py-1 rounded-full
                            @if($item->stock == 0) bg-red-100 text-red-700
                            @elseif($item->stock < 5) bg-yellow-100 text-yellow-700
                            @else bg-green-100 text-green-700 @endif">
                            {{ $item->stock }}
                        </span>
                    </td>

                    <td class="px-6 py-3 text-center">
                        @if($item->stock == 0)
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded">
                                Out of Stock
                            </span>
                        @elseif($item->stock < 5)
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded">
                                Low Stock
                            </span>
                        @else
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded">
                                Available
                            </span>
                        @endif
                    </td>

                    <td class="px-6 py-3 text-gray-700 max-w-xs truncate">
                        {{ $item->description ?? '-' }}
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        <p class="text-base">No items found</p>
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>
    </div>

    <!-- PAGINATION -->
    <div class="px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-sm text-gray-600">
            Showing <span class="font-semibold">{{ $items->firstItem() ?? 0 }}</span>
            to <span class="font-semibold">{{ $items->lastItem() ?? 0 }}</span>
            of <span class="font-semibold">{{ $items->total() }}</span> results
        </div>

        <div>
            {{ $items->links('pagination::tailwind') }}
        </div>
    </div>

</div>

<!-- LEGEND -->
<div class="mt-6 bg-white rounded-lg shadow p-6 border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Legend</h3>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="flex items-center gap-3">
            <span class="inline-block w-4 h-4 rounded-full bg-green-100 border border-green-700"></span>
            <span class="text-sm text-gray-700">Available (Stock ≥ 5)</span>
        </div>

        <div class="flex items-center gap-3">
            <span class="inline-block w-4 h-4 rounded-full bg-yellow-100 border border-yellow-700"></span>
            <span class="text-sm text-gray-700">Low Stock (1-4)</span>
        </div>

        <div class="flex items-center gap-3">
            <span class="inline-block w-4 h-4 rounded-full bg-red-100 border border-red-700"></span>
            <span class="text-sm text-gray-700">Out of Stock (0)</span>
        </div>

        <div class="flex items-center gap-3">
            <span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Normal</span>
            <span class="text-sm text-gray-700">Normal Condition</span>
        </div>

    </div>

</div>

<!-- PRINT STYLES -->
<style>
    @media print {
        .hidden-print {
            display: none !important;
        }

        body {
            background: white;
        }

        table {
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #333;
            padding: 8px;
        }

        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }

        .no-print {
            display: none !important;
        }
    }
</style>

@endsection

