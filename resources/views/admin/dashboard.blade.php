@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Analytics</h1>
            <p class="text-gray-600 mt-1">Welcome Back, <span class="font-medium">{{ Auth::user()->name }}</span></p>
        </div>
    </div>

    <!-- Main Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Items Card -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Items</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalItems }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3a1 1 0 000 2h1.05L5.757 9H3a1 1 0 100 2h10.238l.341.682A1 1 0 0014 12H6a1 1 0 00-.894.553l-3 6A1 1 0 004 19h1a1 1 0 100 2H4a3 3 0 01-2.83-4.354l1.423-2.846c.26-.52.023-1.14-.522-1.453-.577-.323-1.279-.33-1.852.164m16.718-7.472L11.55 7H3.868l.502 2.01H16.62l.098-.202z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Incoming Items Card -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Incoming</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalIncoming }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Outgoing Items Card -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Outgoing</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $totalOutgoing }}</p>
                </div>
                <div class="bg-red-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert Card -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Low Stock</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $lowStockCount }}</p>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Master Data Overview Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Categories Card -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Categories</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $totalCategories }}</p>
                    <a href="{{ route('admin.categories.index') }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium mt-3 inline-block">
                        View All →
                    </a>
                </div>
                <div class="bg-indigo-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 000-2H7zM4 7a1 1 0 011-1h10a1 1 0 011 1v3a2 2 0 01-2 2H6a2 2 0 01-2-2V7zm2 5a2 2 0 012-2h8a2 2 0 012 2v3a2 2 0 01-2 2H8a2 2 0 01-2-2v-3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Departments Card -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Departments</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalDepartments }}</p>
                    <a href="{{ route('admin.departement.index') }}" class="text-xs text-purple-600 hover:text-purple-700 font-medium mt-3 inline-block">
                        View All →
                    </a>
                </div>
                <div class="bg-purple-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Suppliers Card -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Suppliers</p>
                    <p class="text-3xl font-bold text-teal-600 mt-2">{{ $totalVendors }}</p>
                    <a href="{{ route('admin.suppliers.index') }}" class="text-xs text-teal-600 hover:text-teal-700 font-medium mt-3 inline-block">
                        View All →
                    </a>
                </div>
                <div class="bg-teal-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Units Card -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Units</p>
                    <p class="text-3xl font-bold text-cyan-600 mt-2">{{ $totalUnits }}</p>
                    <a href="{{ route('admin.units.index') }}" class="text-xs text-cyan-600 hover:text-cyan-700 font-medium mt-3 inline-block">
                        View All →
                    </a>
                </div>
                <div class="bg-cyan-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Incoming -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Recent Incoming
                </h3>
                <a href="{{ route('admin.incoming.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">
                    View All →
                </a>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($recentIncoming as $item)
                    <div class="px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 text-sm">{{ $item->code }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $item->incoming_date->format('d M Y H:i') }}</p>
                            </div>
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">
                                {{ $item->details->sum('quantity') }} items
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm0 2h12v8H3V5z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm">No recent incoming data</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Outgoing -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Recent Outgoing
                </h3>
                <a href="{{ route('admin.outgoing.index') }}" class="text-sm text-red-600 hover:text-red-700 font-medium">
                    View All →
                </a>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($recentOutgoing as $item)
                    <div class="px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 text-sm">{{ $item->code }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $item->outgoing_date->format('d M Y H:i') }}</p>
                            </div>
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded">
                                {{ $item->details->sum('quantity') }} items
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm0 2h12v8H3V5z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm">No recent outgoing data</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Stock Alerts Section -->
    @if($stockAlertItems->count() > 0)
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                Stock Alerts - Below Minimum Stock Level
            </h3>
            <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded">
                {{ $stockAlertItems->count() }} items
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-medium text-gray-700">Item Name</th>
                        <th class="px-6 py-4 text-left font-medium text-gray-700">Category</th>
                        <th class="px-6 py-4 text-center font-medium text-gray-700">Current Stock</th>
                        <th class="px-6 py-4 text-center font-medium text-gray-700">Min Stock</th>
                        <th class="px-6 py-4 text-center font-medium text-gray-700">Deficit</th>
                        <th class="px-6 py-4 text-center font-medium text-gray-700">Unit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($stockAlertItems as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 font-medium">{{ $item->item_name }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $item->category->category_name ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded">{{ $item->stock }}</span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-900">{{ $item->min_stock }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded">{{ $item->min_stock - $item->stock }}</span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700">{{ $item->unit->unit_name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

@endsection

