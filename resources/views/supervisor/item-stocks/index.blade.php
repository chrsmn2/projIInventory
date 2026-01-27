@extends('layouts.supervisor')

@section('title', 'Stock Monitoring')

@section('content')

<div class="bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="flex items-center justify-between px-6 py-4 rounded-t-xl
                bg-gradient-to-r from-gray-700 to-gray-800">

        <div>
            <h2 class="text-xl font-semibold text-white">Stock Monitoring</h2>
            <p class="text-sm text-gray-300">
                Monitor inventory stock levels and status
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
                        class="px-3 py-2 border rounded-lg text-sm w-full sm:w-48 focus:ring focus:ring-blue-200">

                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
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
                           px-4 py-2 bg-gray-600 text-white rounded-lg text-sm
                           hover:bg-gray-700 transition">
                📄 Export PDF
            </button>

        </div>

    </div>

    <!-- STATS SECTION -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-6 border-b border-gray-200">

        <!-- Total Items -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-sm transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Items</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalItems }}</p>
                </div>
                <div class="bg-blue-100 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3a1 1 0 000 2h1.05L5.757 9H3a1 1 0 100 2h10.238l.341.682A1 1 0 0014 12H6a1 1 0 00-.894.553l-3 6A1 1 0 004 19h1a1 1 0 100 2H4a3 3 0 01-2.83-4.354l1.423-2.846c.26-.52.023-1.14-.522-1.453-.577-.323-1.279-.33-1.852.164m16.718-7.472L11.55 7H3.868l.502 2.01H16.62l.098-.202z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Stock -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-sm transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Stock</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalStock }}</p>
                </div>
                <div class="bg-green-100 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Low Stock Items -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-sm transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Low Stock (&lt;5)</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $lowStockCount }}</p>
                </div>
                <div class="bg-yellow-100 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Zero Stock -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-sm transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Out of Stock</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $outOfStockCount }}</p>
                </div>
                <div class="bg-red-100 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    <!-- TABLE SECTION -->
    <div class="p-6 overflow-x-auto">
        <table class="w-full min-w-[800px] text-sm border border-gray-200 rounded-lg overflow-hidden">

            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Item Code</th>
                    <th class="px-4 py-3 text-left">Item Name</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-center">Condition</th>
                    <th class="px-4 py-3 text-center">Stock</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-left">Description</th>
                </tr>
            </thead>

            <tbody class="divide-y bg-white">
                @forelse($items as $index => $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-900">
                        {{ $items->firstItem() + $index }}
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900">
                        {{ $item->item_code }}
                    </td>
                    <td class="px-4 py-3 text-gray-900">
                        {{ $item->item_name }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $item->category->category_name ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($item->condition === 'good') bg-green-100 text-green-800
                            @elseif($item->condition === 'damaged') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($item->condition ?? 'unknown') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center font-semibold">
                        {{ $item->stock }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($item->stock == 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Out of Stock
                            </span>
                        @elseif($item->stock < 5)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Low Stock
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                In Stock
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                        {{ $item->description ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-5v2m0 0v2m0-2h2m-2 0h-2"/>
                            </svg>
                            <p class="text-lg font-medium">No items found</p>
                            <p class="text-sm">Try adjusting your search or filter criteria.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION -->
        @if($items->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $items->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>

@endsection