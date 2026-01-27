@extends('layouts.supervisor')

@section('title', 'Loan Monitoring')

@section('content')

<div class="bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
    <div class="flex items-center justify-between px-6 py-4 rounded-t-xl
                bg-gradient-to-r from-gray-700 to-gray-800">

        <div>
            <h2 class="text-xl font-semibold text-white">Loan Monitoring</h2>
            <p class="text-sm text-gray-300">
                Monitor all loan requests and their status
            </p>
        </div>

        <!-- RIGHT SIDE: Search & Filter -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full md:w-auto">

            <!-- SEARCH -->
            <form method="GET" class="flex gap-2 flex-1 sm:flex-none">
                <input type="text"
                    name="search"
                    value="{{ request('search', '') }}"
                    placeholder="Search loans..."
                    class="px-3 py-2 border rounded-lg text-sm w-full sm:w-48 focus:ring focus:ring-blue-200">

                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                    Search
                </button>
            </form>

            <!-- STATUS FILTER -->
            <form method="GET" class="flex items-center">
                <input type="hidden" name="search" value="{{ request('search', '') }}">
                <select name="status"
                        onchange="this.form.submit()"
                        class="px-3 py-2 border rounded-lg text-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                </select>
            </form>

        </div>

    </div>

    <!-- STATS SECTION -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 p-6 border-b border-gray-200">

        <!-- Total Loans -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-sm transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Loans</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalLoans }}</p>
                </div>
                <div class="bg-blue-100 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-sm transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Pending</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $pendingLoans }}</p>
                </div>
                <div class="bg-yellow-100 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-sm transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Approved</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $approvedLoans }}</p>
                </div>
                <div class="bg-green-100 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-sm transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Rejected</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $rejectedLoans }}</p>
                </div>
                <div class="bg-red-100 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Returned -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-sm transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Returned</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $returnedLoans }}</p>
                </div>
                <div class="bg-purple-100 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    <!-- TABLE SECTION -->
    <div class="p-6 overflow-x-auto">
        <table class="w-full min-w-[1000px] text-sm border border-gray-200 rounded-lg overflow-hidden">

            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Loan Code</th>
                    <th class="px-4 py-3 text-left">Requester</th>
                    <th class="px-4 py-3 text-left">Item</th>
                    <th class="px-4 py-3 text-center">Quantity</th>
                    <th class="px-4 py-3 text-center">Loan Date</th>
                    <th class="px-4 py-3 text-center">Return Date</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-left">Approved By</th>
                </tr>
            </thead>

            <tbody class="divide-y bg-white">
                @forelse($loans as $index => $loan)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-900">
                        {{ $loans->firstItem() + $index }}
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900">
                        {{ $loan->loan_code }}
                    </td>
                    <td class="px-4 py-3 text-gray-900">
                        {{ $loan->user->name ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-3 text-gray-900">
                        {{ $loan->item->item_name ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        {{ $loan->quantity }}
                    </td>
                    <td class="px-4 py-3 text-center text-gray-600">
                        {{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-center text-gray-600">
                        {{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($loan->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($loan->status === 'approved') bg-green-100 text-green-800
                            @elseif($loan->status === 'rejected') bg-red-100 text-red-800
                            @elseif($loan->status === 'returned') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($loan->status ?? 'unknown') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $loan->approvedBy->name ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-lg font-medium">No loans found</p>
                            <p class="text-sm">Try adjusting your search or filter criteria.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION -->
        @if($loans->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $loans->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>

@endsection