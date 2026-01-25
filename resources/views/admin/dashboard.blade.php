@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard Analitik</h1>
    <p class="text-gray-600 mt-1">Welcome back, <span class="font-semibold">{{ Auth::user()->name }}</span>. Here's your inventory overview.</p>
</div>

<!-- ===================== -->
<!-- MAIN STATS CARDS -->
<!-- ===================== -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <!-- Total Items -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-xl shadow-lg text-white transform hover:scale-105 transition">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold">Total Items</p>
                <p class="text-4xl font-bold mt-3">{{ $totalItems }}</p>
            </div>
            <div class="bg-blue-400 bg-opacity-30 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3a1 1 0 000 2h1.05L5.757 9H3a1 1 0 100 2h10.238l.341.682A1 1 0 0014 12H6a1 1 0 00-.894.553l-3 6A1 1 0 004 19h1a1 1 0 100 2H4a3 3 0 01-2.83-4.354l1.423-2.846c.26-.52.023-1.14-.522-1.453-.577-.323-1.279-.33-1.852.164m16.718-7.472L11.55 7H3.868l.502 2.01H16.62l.098-.202z"/>
                </svg>
            </div>
        </div>
        <p class="text-xs mt-4 text-blue-100">Total inventory items</p>
    </div>

    <!-- Total Incoming -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-xl shadow-lg text-white transform hover:scale-105 transition">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold">Incoming Items</p>
                <p class="text-4xl font-bold mt-3">{{ $totalIncoming }}</p>
            </div>
            <div class="bg-orange-400 bg-opacity-30 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
        <p class="text-xs mt-4 text-orange-100">Total incoming transactions</p>
    </div>

    <!-- Total Outgoing -->
    <div class="bg-gradient-to-br from-red-500 to-red-600 p-6 rounded-xl shadow-lg text-white transform hover:scale-105 transition">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold">Outgoing Items</p>
                <p class="text-4xl font-bold mt-3">{{ $totalOutgoing }}</p>
            </div>
            <div class="bg-red-400 bg-opacity-30 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
        <p class="text-xs mt-4 text-red-100">Total outgoing transactions</p>
    </div>

    <!-- Total Stock Value -->
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 rounded-xl shadow-lg text-white transform hover:scale-105 transition">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold">Stock Alert</p>
                <p class="text-4xl font-bold mt-3">{{ $lowStockCount }}</p>
            </div>
            <div class="bg-emerald-400 bg-opacity-30 p-3 rounded-lg">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
        <p class="text-xs mt-4 text-emerald-100">Items below minimum stock</p>
    </div>

</div>

<!-- ===================== -->
<!-- MASTER DATA STATS -->
<!-- ===================== -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <!-- Categories -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-700 font-semibold">Categories</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalCategories }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M7 3a1 1 0 000 2h6a1 1 0 000-2H7zM4 7a1 1 0 011-1h10a1 1 0 011 1v3a2 2 0 01-2 2H6a2 2 0 01-2-2V7zm2 5a2 2 0 012-2h8a2 2 0 012 2v3a2 2 0 01-2 2H8a2 2 0 01-2-2v-3z"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-semibold mt-4 inline-block">
            View All →
        </a>
    </div>

    <!-- Departments -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-700 font-semibold">Departments</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalDepartments }}</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.departement.index') }}" class="text-xs text-purple-600 hover:text-purple-700 font-semibold mt-4 inline-block">
            View All →
        </a>
    </div>

    <!-- Vendors -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-700 font-semibold">Vendors</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalVendors }}</p>
            </div>
            <div class="bg-indigo-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.suppliers.index') }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-semibold mt-4 inline-block">
            View All →
        </a>
    </div>

    <!-- Units -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-700 font-semibold">Units</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalUnits }}</p>
            </div>
            <div class="bg-cyan-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.units.index') }}" class="text-xs text-cyan-600 hover:text-cyan-700 font-semibold mt-4 inline-block">
            View All →
        </a>
    </div>

</div>

<!-- ===================== -->
<!-- RECENT ACTIVITIES -->
<!-- ===================== -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    <!-- Recent Incoming -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
        <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white flex items-center justify-between">
            <h3 class="text-lg font-bold flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                Recent Incoming
            </h3>
            <a href="{{ route('admin.incoming.index') }}" class="bg-orange-700 hover:bg-orange-800 px-3 py-1 rounded text-xs font-semibold transition">View All</a>
        </div>
        <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
            @forelse($recentIncoming as $item)
                <div class="px-6 py-4 hover:bg-orange-50 transition cursor-pointer">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 text-sm">{{ $item->code }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $item->incoming_date->format('d M Y H:i') }}</p>
                        </div>
                        <span class="bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap ml-2">
                            {{ $item->details->sum('quantity') }}x
                        </span>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm0 2h12v8H3V5z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm">No incoming data</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Outgoing -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
        <div class="px-6 py-4 bg-gradient-to-r from-red-500 to-red-600 text-white flex items-center justify-between">
            <h3 class="text-lg font-bold flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
                Recent Outgoing
            </h3>
            <a href="{{ route('admin.outgoing.index') }}" class="bg-red-700 hover:bg-red-800 px-3 py-1 rounded text-xs font-semibold transition">View All</a>
        </div>
        <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
            @forelse($recentOutgoing as $item)
                <div class="px-6 py-4 hover:bg-red-50 transition cursor-pointer">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 text-sm">{{ $item->code }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $item->outgoing_date->format('d M Y H:i') }}</p>
                        </div>
                        <span class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap ml-2">
                            {{ $item->details->sum('quantity') }}x
                        </span>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm0 2h12v8H3V5z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm">No outgoing data</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

<!-- ===================== -->
<!-- ADDITIONAL STATS -->
<!-- ===================== -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Normal Items -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition border-l-4 border-l-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-700 font-semibold">Normal Items</p>
                <p class="text-3xl font-bold text-green-600 mt-2">
                    {{ \App\Models\Item::where('condition', 'normal')->count() }}
                </p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-3">In good condition</p>
    </div>

    <!-- Damaged Items -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition border-l-4 border-l-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-700 font-semibold">Damaged Items</p>
                <p class="text-3xl font-bold text-red-600 mt-2">
                    {{ \App\Models\Item::where('condition', 'damaged')->count() }}
                </p>
            </div>
            <div class="bg-red-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 105.11 2.623a.75.75 0 00-1.08.102A6.001 6.001 0 0019.89 13.477.75.75 0 0013.477 14.89z"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-3">Requires repair/replacement</p>
    </div>

    <!-- Lost Items -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition border-l-4 border-l-gray-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-700 font-semibold">Lost Items</p>
                <p class="text-3xl font-bold text-gray-600 mt-2">
                    {{ \App\Models\Item::where('condition', 'lost')->count() }}
                </p>
            </div>
            <div class="bg-gray-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 105.11 2.623a.75.75 0 00-1.08.102A6.001 6.001 0 0019.89 13.477.75.75 0 0013.477 14.89zM9 13a1 1 0 100-2 1 1 0 000 2zm4-4a1 1 0 10-2 0 1 1 0 002 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-3">Not found in inventory</p>
    </div>
</div>

<!-- ===================== -->
<!-- STOCK ALERT SECTION -->
<!-- ===================== -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
    <div class="px-6 py-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white flex items-center justify-between">
        <h3 class="text-lg font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            Stock Alert - Below Minimum Stock Level
        </h3>
        <span class="bg-yellow-700 px-3 py-1 rounded-full text-sm font-bold">{{ $stockAlertItems->count() }} items</span>
    </div>
    <div class="overflow-x-auto">
        @if($stockAlertItems->count() > 0)
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Item Name</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Category</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Current Stock</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Min Stock</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Deficit</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Unit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($stockAlertItems as $item)
                        <tr class="hover:bg-red-50 transition border-l-4 border-yellow-500">
                            <td class="px-6 py-4 text-gray-900 font-semibold">{{ $item->item_name }}</td>
                            <td class="px-6 py-4 text-gray-700 text-sm">{{ $item->category->category_name ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-yellow-100 text-yellow-800 font-bold px-3 py-1 rounded-full">
                                    {{ $item->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-900 font-semibold">{{ $item->min_stock }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-red-100 text-red-800 font-bold px-3 py-1 rounded-full">
                                    {{ $item->min_stock - $item->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700 text-sm">{{ $item->unit->unit_name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="px-6 py-16 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-lg font-semibold text-gray-700">All items have sufficient stock</p>
                <p class="text-sm text-gray-500 mt-2">All inventory items are above the minimum stock level</p>
            </div>
        @endif
    </div>
</div>

@endsection

