@extends('layouts.admin')

@section('title', 'Transaction Report')

@section('content')

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Transaction Report</h1>
            <p class="text-gray-600 mt-1">Track incoming and outgoing item movements with detailed items list</p>
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

    <!-- Filters Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter & Export</h3>
        
        <form method="GET" id="filterForm" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" onchange="document.getElementById('filterForm').submit();" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="incoming" {{ request('type', 'incoming') == 'incoming' ? 'selected' : '' }}>Incoming Items</option>
                        <option value="outgoing" {{ request('type') == 'outgoing' ? 'selected' : '' }}>Outgoing Items</option>
                    </select>
                </div>

                <!-- Month Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                    <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('month', now()->month) == $i ? 'selected' : '' }}>
                                {{ now()->createFromFormat('m', $i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Year Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                    <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @for ($i = now()->year; $i >= now()->year - 5; $i--)
                            <option value="{{ $i }}" {{ request('year', now()->year) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Supplier Filter (only for incoming) -->
                @if (request('type', 'incoming') == 'incoming')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <select name="supplier" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">All Suppliers</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ request('supplier') == $sup->id ? 'selected' : '' }}>
                                {{ $sup->supplier_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Per Page -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Per Page</label>
                    <select name="per_page" onchange="document.getElementById('filterForm').submit();"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @foreach([5,10,15,25] as $size)
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

                <a href="{{ route('admin.reports.movement') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium">
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

    <!-- Transactions with Details -->
    <div class="space-y-4">
        @forelse ($movements as $index => $movement)
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <!-- Transaction Header -->
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-600 font-medium uppercase">Code</p>
                            <p class="text-sm font-bold text-blue-600 mt-1">{{ $movement->code ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 font-medium uppercase">Date</p>
                            <p class="text-sm font-medium text-gray-900 mt-1">
                                @if (request('type', 'incoming') == 'incoming')
                                    {{ \Carbon\Carbon::parse($movement->incoming_date)->format('d-m-Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($movement->outgoing_date)->format('d-m-Y') }}
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 font-medium uppercase">Admin</p>
                            <p class="text-sm font-medium text-gray-900 mt-1">{{ $movement->admin?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 font-medium uppercase">
                                @if (request('type', 'incoming') == 'incoming')
                                    Supplier
                                @else
                                    Requester / Departement
                                @endif
                            </p>
                            <p class="text-sm font-medium text-gray-900 mt-1">
                                @if (request('type', 'incoming') == 'incoming')
                                    {{ $movement->supplier?->supplier_name ?? '-' }}
                                @else
                                    {{ $movement->departement?->departement_name ?? $movement->destination ?? '-' }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Items Detail Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-900">Item Code</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-900">Item Name</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-900">Quantity</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-900">Unit</th>
                                @if (request('type', 'incoming') == 'incoming' && $movement->details?->first()?->notes)
                                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Notes</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($movement->details ?? [] as $detail)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        <span class="font-mono text-blue-600 font-semibold">{{ $detail->item?->item_code ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-gray-900 font-medium">
                                        {{ $detail->item?->item_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3 text-center font-bold text-gray-900">
                                        {{ $detail->quantity }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-700">
                                        {{ $detail->unit?->unit_name ?? '-' }}
                                    </td>
                                    @if (request('type', 'incoming') == 'incoming' && $movement->details?->first()?->notes)
                                        <td class="px-6 py-3 text-gray-600 text-xs">
                                            {{ $detail->notes ?? '-' }}
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 text-sm">
                                        No items in this transaction
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="font-medium text-gray-900">No transactions found</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $movements->links() }}
    </div>
</div>

@endsection